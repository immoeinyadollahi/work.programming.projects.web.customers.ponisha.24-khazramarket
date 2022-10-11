<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Traits\Helpers\ApiResponseTrait;
use App\Http\Resources\Api\V1\Order\OrderCollection;
use App\Http\Resources\Api\V1\Order\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\Order\StoreOrderRequest;
use App\Events\OrderCreated;
use App\Events\OrderPaid;
use App\Jobs\CancelOrder;
use App\Jobs\DeleteTempAuthToken;
use App\Models\Gateway;
use App\Models\Option;
use App\Models\Transaction;
use App\Models\WalletHistory;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Shetabit\Payment\Facade\Payment;
use Shetabit\Multipay\Invoice;

class OrderController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        $per_page = $request->per_page ?: 20;
        $orders   = $request->user()->orders()->latest()->paginate($per_page);

        return $this->respondWithResourceCollection(new OrderCollection($orders));
    }

    public function show(Order $order)
    {
        return $this->respondWithResource(new OrderResource($order));
    }
    public function store(StoreOrderRequest $request)
    {
        $user = auth("sanctum")->user();

        $cart = $user->cart;
        $gateway  = Gateway::where('key', $request->gateway)->first();
        $data     = $request->validated();

        $data['shipping_cost']      = $cart->shippingCostAmount();
        $data['price']              = $cart->finalPrice();
        $data['status']             = 'unpaid';
        $data['discount_amount']    = $cart->totalDiscount();
        $data['discount_id']        = $cart->discount_id;
        $data['user_id']            = $user->id;

        if ($gateway) {
            $data['gateway_id']         = $gateway->id;
        }

        $carrier_result = $cart->canUseCarrier($request->carrier_id);

        if ($cart->hasPhysicalProduct() && !$carrier_result['status']) {

            return $this->apiResponse(
                [
                    'success' => false,
                    'errors' => [
                        'carrier_id' => $carrier_result['message'],
                    ]
                ],
                422
            );
        }

        $order = Order::create($data);

        //add cart products to order
        foreach ($cart->products as $product) {

            $price = $product->prices()->find($product->pivot->price_id);

            if ($price) {
                $order->items()->create([
                    'product_id'      => $product->id,
                    'title'           => $product->title,
                    'price'           => $price->discountPrice(),
                    'real_price'      => $price->tomanPrice(),
                    'quantity'        => $product->pivot->quantity,
                    'discount'        => $price->discount,
                    'price_id'        => $product->pivot->price_id,
                ]);
            }
        }

        $cart->delete();

        // cancel order after $hour hours
        $hour = option('order_cancel', 1);
        CancelOrder::dispatch($order)->delay(now()->addHours($hour));

        event(new OrderCreated($order));

        return $this->pay($order, $request);
    }
    public function tempCheckoutLink()
    {
        $user = auth("sanctum")->user();
        $token = Crypt::encrypt(["user_id" => $user->id, "redirect_url" => route("front.checkout")]);
        $option = Option::create(["option_name" => "user_" . $user->id . "_auth_token", "option_value" => $token]);
        DeleteTempAuthToken::dispatch($option->id)->delay(now()->addMinutes(2));
        return $this->apiResponse(["result" => ["url" => route("front.auth-check", ["token" => $token])]]);
    }
    public function pay(Order $order, Request $request)
    {
        if ($order->price == 0) {
            return $this->orderPaid($order);
        }

        $gateways = Gateway::active()->pluck('key')->toArray();

        $request->validate([
            'gateway' => 'required|in:wallet,' . implode(',', $gateways)
        ]);

        $gateway = $request->gateway;

        if ($gateway == 'wallet') {
            return $this->payUsingWallet($order);
        }

        try {
            $user = auth("sanctum")->user();
            $gateway_configs = get_gateway_configs($gateway);

            return Payment::via($gateway)->config($gateway_configs)->callbackUrl("")->purchase(
                (new Invoice)->amount(intval($order->price)),
                function ($driver, $transactionId) use ($order, $gateway, $user) {
                    DB::table('transactions')->insert([
                        'status'               => false,
                        'amount'               => $order->price,
                        'factorNumber'         => $order->id,
                        'mobile'               => $user->username,
                        'message'              => trans('front::messages.controller.port-transaction') . $gateway,
                        'transID'              => (string) $transactionId,
                        'token'                => (string) $transactionId,
                        'user_id'              => $user->id,
                        'transactionable_type' => Order::class,
                        'transactionable_id'   => $order->id,
                        'gateway_id'           => Gateway::where('key', $gateway)->first()->id,
                        "created_at"           => Carbon::now(),
                        "updated_at"           => Carbon::now(),
                    ]);
                }
            )->pay()->jsonSerialize();
        } catch (Exception $e) {
            return $this->apiResponse(
                [
                    'success' => false,
                    'errors' => [
                        'transaction-error' => $e->getMessage(),
                    ]
                ],
                422
            );
        }
    }

    public function verify($gateway)
    {
        $req = request();
        $transaction = Transaction::where('status', false)->where('transID', $req->input("transaction_id"))->firstOrFail();

        $order = $transaction->transactionable;

        $gateway_configs = get_gateway_configs($gateway);

        try {
            $receipt = Payment::via($gateway)->config($gateway_configs)->amount(intval($transaction->amount))->transactionId($transaction->transID)->verify();

            $transaction->update([
                'status'               => 1,
                'amount'               => $order->price,
                'factorNumber'         => $order->id,
                'mobile'               => $order->mobile,
                'traceNumber'          => $receipt->getReferenceId(),
                'message'              => $transaction->message . '<br>' . trans('front::messages.controller.successful-payment') . $gateway,
                'updated_at'           => Carbon::now(),
            ]);
            return $this->orderPaid($order);
        } catch (\Exception $exception) {

            $transaction->update([
                'message'              => $transaction->message . '<br>' . $exception->getMessage(),
                "updated_at"           => Carbon::now(),
            ]);
            return $this->apiResponse(
                [
                    'success' => false,
                    'errors' => [
                        'transaction-error' => $exception->getMessage(),
                    ]
                ],
                422
            );
        }
    }
    private function payUsingWallet(Order $order)
    {
        $wallet  = $order->user->getWallet();
        $amount  = intval($wallet->balance() - $order->price);

        if ($amount >= 0) {
            $result = $order->payUsingWallet();

            if ($result) {
                return $this->orderPaid($order);
            }
        }

        $gateway = Gateway::active()->orderBy('ordering')->first();
        $amount  = abs($amount);

        if (!$gateway) {
            return $this->apiResponse(
                [
                    'success' => false,
                    'errors' => [
                        'transaction-error' => trans('front::messages.controller.active-port'),
                    ]
                ],
                422
            );
        }

        $history = $wallet->histories()->create([
            'type'        => 'deposit',
            'amount'      => $amount,
            'description' => trans('front::messages.controller.online-wallet-recharge'),
            'source'      => 'user',
            'status'      => 'fail',
            'order_id'    => $order->id
        ]);

        try {
            $gateway         = $gateway->key;
            $gateway_configs = get_gateway_configs($gateway);
            $user = auth("sanctum")->user();
            return Payment::via($gateway)->config($gateway_configs)->callbackUrl("")->purchase(
                (new Invoice)->amount($amount),
                function ($driver, $transactionId) use ($history, $gateway, $amount, $user) {
                    DB::table('transactions')->insert([
                        'status'               => false,
                        'amount'               => $amount,
                        'factorNumber'         => $history->id,
                        'mobile'               => $user->username,
                        'message'              => trans('front::messages.controller.port-transaction') . $gateway,
                        'transID'              => $transactionId,
                        'token'                => $transactionId,
                        'user_id'              => $user->id,
                        'transactionable_type' => WalletHistory::class,
                        'transactionable_id'   => $history->id,
                        'gateway_id'           => Gateway::where('key', $gateway)->first()->id,
                        "created_at"           => Carbon::now(),
                        "updated_at"           => Carbon::now(),
                    ]);
                }
            )->pay()->jsonSerialize();
        } catch (Exception $e) {
            return $this->apiResponse(
                [
                    'success' => false,
                    'errors' => [
                        'transaction-error' => $e->getMessage(),
                    ]
                ],
                422
            );
        }
    }

    private function orderPaid(Order $order)
    {
        $order->update([
            'status' => 'paid',
        ]);

        event(new OrderPaid($order));

        return response()->noContent(200);
    }
}
