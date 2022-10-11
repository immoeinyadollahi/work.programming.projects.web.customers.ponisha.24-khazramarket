<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Returned extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'description',
        'user_id',
        'reject_reason',
        'seen',
        'TrackingCode'
    ];

    public function orderitem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeFilter($query)
    {
        return $query;
    }
}
