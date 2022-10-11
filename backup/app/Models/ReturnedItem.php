<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnedItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'returned_id',
        'order_item_id',
        'quantity',
        'accepted_count',
        'ConfirmedCount',
        'RejectedCount',
        'reject_reason'
    ];

    public function orderitem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }
}
