<?php

namespace App\Models;

use App\Traits\Languageable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory, Languageable;

    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(Category::class, "category_id");
    }
}
