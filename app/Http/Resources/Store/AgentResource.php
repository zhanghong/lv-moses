<?php

namespace App\Http\Resources\Store;

use Illuminate\Http\Resources\Json\JsonResource;

class AgentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'name' => $this->name,
            'contact_name' => $this->contact_name,
            'contact_phone' => $this->contact_phone,
            'store_count' => $this->store_count,
            'can_update' => $this->can_update,
            'can_delete' => $this->can_delete,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
