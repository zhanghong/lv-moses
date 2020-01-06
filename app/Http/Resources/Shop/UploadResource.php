<?php

namespace App\Http\Resources\Shop;

use Illuminate\Http\Resources\Json\JsonResource;

class UploadResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'attach_type' => $this->attach_type,
            'file_url' => $this->file_url,
            'file_size' => $this->file_size,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
