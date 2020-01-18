<?php

namespace App\Http\Resources\Base;

use Illuminate\Http\Resources\Json\JsonResource;

class AreaListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'parent_id' => $this->parent_id,
            'is_directory' => $this->is_directory,
        ];
    }
}
