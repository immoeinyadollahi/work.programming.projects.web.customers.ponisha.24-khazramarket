<?php

namespace App\Http\Resources\Api\V1\Slider;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'image'          => $this->image ? asset($this->image) : null,
            'title'          => $this->title,
            'description'    => $this->description,
            'link'    => $this->link,
            'group'    => $this->group,
            'category'    => $this->category,
            'ordering'    => $this->ordering,
            'created_at'    => $this->created_at,
        ];
    }
}
