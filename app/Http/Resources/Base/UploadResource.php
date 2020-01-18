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
            'ownable_type' => $this->ownable_type,
            'ownable_id' => $this->ownable_id,
            'attachable_type' => $this->attachable_type,
            'attachable_id' => $this->attachable_id,
            'attach_type' => $this->attach_type,
            'file_url' => $this->file_url,
            'file_size' => $this->file_size,
            'order' => $this->order,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
