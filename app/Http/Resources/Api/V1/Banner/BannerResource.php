<?php

namespace App\Http\Resources\Api\V1\Banner;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
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
            'link'    => $this->link,
            'group'    => $this->group,
            'category'    => $this->category,
            'ordering'    => $this->ordering,
            'created_at'    => $this->created_at,
        ];
    }
}
