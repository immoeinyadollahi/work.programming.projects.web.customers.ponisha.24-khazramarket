<?php

namespace App\Models;

use App\Traits\Languageable;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use Languageable;

    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(Category::class, "category_id");
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }
}
