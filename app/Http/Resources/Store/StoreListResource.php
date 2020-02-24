<?php

namespace App\Http\Resources\Store;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreListResource extends JsonResource
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
            'shop_id' => $this->shop_id,
            'agent_id' => $this->agent_id,
            'agent_name' => $this->agent ? $this->agent->name : '',
            'name' => $this->name,
            'auth_no' => $this->auth_no,
            'area_id' => $this->area_id,
            'full_address' => $this->full_address_str,
            'address' => $this->address,
            'staff_count' => $this->staff_count,
            'order' => $this->order,
            'can_update' => $this->can_update,
            'can_delete' => $this->can_delete,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
