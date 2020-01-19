<?php

namespace App\Http\Resources\Store;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Base\UploadResource;
use App\Http\Resources\Store\AgentResource;

class StoreDetailResource extends JsonResource
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
            'agent' => new AgentResource($this->agent),
            'name' => $this->name,
            'auth_no' => $this->auth_no,
            'area_id' => $this->area_id,
            'area_full_name' => $this->area ? $this->area->full_name : '',
            'staff_count' => 0,
            'images' => UploadResource::collection($this->images),
            'can_update' => $this->can_update,
            'can_delete' => $this->can_delete,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
