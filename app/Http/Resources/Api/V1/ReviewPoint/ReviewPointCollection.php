<?php

namespace App\Http\Resources\Api\V1\ReviewPoint;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ReviewPointCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($review_point) {
            return [
                'id'             => $review_point->id,
                'text'           => $review_point->title,
                'type'           => $review_point->body,
                'created_at'     => jdate($review_point->created_at)->ago(),
            ];
        });
    }
}
