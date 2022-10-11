<?php

namespace App\Http\Resources\Api\V1\Review;

use App\Http\Resources\Api\V1\ReviewPoint\ReviewPointCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReviewCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($review) {
            return [
                'id'             => $review->id,
                'user_id'        => $review->user_id,
                'title'           => $review->title,
                'body'           => $review->body,
                'likes_count' => $review->likes_count,
                'dislikes_count' => $review->dislikes_count,
                'suggest' => $review->suggest,
                'rating' => $review->rating,
                'points' => new ReviewPointCollection($review->points),
                'created_at'     => jdate($review->created_at)->ago(),
            ];
        });
    }
}
