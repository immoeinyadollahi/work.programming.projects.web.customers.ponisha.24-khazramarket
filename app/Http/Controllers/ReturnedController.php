<?php

namespace App\Http\Controllers;

use App\Http\Resources\Datatable\returned\ReturnedCollection;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Returned;
use App\Models\ReturnedItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReturnedController extends Controller
{
    public function show()
    {
        $returns = Returned::orderBy('updated_at', 'desc')->paginate(10);

        return view('back.returned.index', compact('returns'));
    }
    public function update($id, Request $request)
    {

        $this->validate($request, [
            'status' => 'required',
            'reject_reason' => 'required'
        ]);
        $return = Returned::where('id', $id)->first();

        $return->update([
            'status' => $request->status,
            'reject_reason' => $request->reject_reason,
        ]);
        return redirect()->back();
    }
    public function index(Returned $returned)
    {
        $item_return = ReturnedItem::where('returned_id', $returned->id)->get();
        $returned->update(['seen' => 1]);
        return view('back.returned.index_product', compact('item_return', 'returned'));
    }
}
