<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'name' => $this->name,
            'icon_url' => $this->icon_url,
            'parent_id' => $this->parent_id,
            'is_directory' => $this->is_directory,
            'level' => $this->level,
            'path' => $this->path,
            'order' => $this->order,
            'is_enabled' => $this->is_enabled,
            'can_update' => $this->can_update,
            'can_delete' => $this->can_delete,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
