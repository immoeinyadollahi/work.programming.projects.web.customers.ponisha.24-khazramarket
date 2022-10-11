<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function engage($review_id)
    {
        $review = Review::findOrFail($review_id);
        request()->validate([
            'type' => 'required|string|in:like,dislike',
        ]);
        $review->likes()->updateOrCreate(
            [
                'user_id' => auth()->user()->id
            ],
            [
                'type' => request("type")
            ],
        );

        $review->refreshLikesCount();

        return $this->respondSuccess();
    }
}
