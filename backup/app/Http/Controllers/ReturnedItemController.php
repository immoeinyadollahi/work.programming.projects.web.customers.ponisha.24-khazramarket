<?php

namespace App\Http\Controllers;

use App\Models\ReturnedItem;
use Illuminate\Http\Request;

class ReturnedItemController extends Controller
{
    public function update(ReturnedItem $return, Request $request)
    {
        $this->validate($request, [
            'confirmation_count' => 'required|numeric',
            'accepted_count'     => 'required|numeric',
            'reject_count'       => 'required|numeric',
            'reject_reason'      => 'required',
        ]);
        $count = $request->confirmation_count + $request->accepted_count + $request->reject_count;
        if ($return->quantity == $count) {
            $return->update([
                'accepted_count' => $request->accepted_count,
                'ConfirmedCount' => $request->confirmation_count,
                'RejectedCount'  => $request->reject_count,
                'reject_reason' => $request->reject_reason


            ]);
            return back()->with('success', 'مرجوعی مورد نظر با موفقیت تغییر وضعیت داد');
        } else {
            return back()->with('error', 'متاسفانه تعداد کالا های شما با تعداد کالا های مرجوعی همخوانی ندارد ');
        }
    }
}
