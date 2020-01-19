<?php

namespace App\Http\Resources\Base;

use Illuminate\Http\Resources\Json\JsonResource;

class UploadResource extends JsonResource
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
            'attachable_type' => $this->attachable_type,
            'attachable_id' => $this->attachable_id,
            'attach_type' => $this->attach_type,
            'name' => $this->origin_name,
            'url' => $this->file_url,
            'file_size' => $this->file_size,
            'order' => $this->order,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
