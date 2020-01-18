<?php

namespace App\Http\Resources\Base;

use Illuminate\Http\Resources\Json\JsonResource;

class AreaDetailResource extends JsonResource
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
            'full_name' => $this->full_name,
            'parent_id' => $this->parent_id,
            'is_directory' => $this->is_directory,
            'level' => $this->level,
            'is_enabled' => $this->is_enabled,
            'path_ids' => $this->path_ids,
        ];
    }
}
