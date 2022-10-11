<?php

namespace Themes\DefaultTheme\src\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Returned;
use App\Models\ReturnedItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReturnedController extends Controller
{


    public function create($id)
    {
        $order = auth()->user()->orders()->findOrFail($id);

        return view('front::returned.create', compact('order'));
    }

    public function store(Request $request, Order $order)
    {

        if ($order->user_id != auth()->id()) abort(403);

        $this->validate($request, [
            'description'                  => 'required',
            'return_items'                 => 'required|array',
            'return_items.*.item_id' => ['required', Rule::exists('order_items', 'id')->where('order_id', $order->id)]
        ]);


        $returned = Returned::create([
            'order_id' => $order->id,
            'description'   => $request->description,
            'user_id'       => auth()->id()
        ]);


        foreach ($request->return_items as $items) {
            if (isset($items['checked']) == true)
                ReturnedItem::create([
                    'returned_id' => $returned->id,
                    'order_item_id' => $items['item_id'],
                    'quantity' => $items['quantity']

                ]);
        }
        return redirect()->back();
    }

    public function index()
    {
        $orderItem = OrderItem::latest()->get();
        $returns = Returned::where('user_id', auth()->id())->with('orderitem', 'product')->paginate(10);

        return view('front::returned.index', compact('returns', 'orderItem'));
    }

    public function index_product(Returned $returned)
    {

        if ($returned->user_id != auth()->id()) abort(403);

        $item_return = ReturnedItem::where('returned_id', $returned->id)->get();

        return view('front::returned.index_products', compact('returned', 'item_return'));
    }

    public function traking(Returned $return, Request $request)
    {
        $return->update([
            'TrackingCode' => $request->traking
        ]);
        return redirect()->back();
    }
}
