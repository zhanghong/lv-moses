<?php

namespace App\Http\Resources\Shop;

use Illuminate\Http\Resources\Json\JsonResource;

class ConfigResource extends JsonResource
{
    public function toArray($request)
    {

        $shop = [
            'id' => $this->id,
            'manager_id' => $this->manager_id,
            'name' => $this->name,
            'main_image_url' => $this->main_image_url,
            'store_count' => $this->store_count,
            'order' => $this->order,
            'is_default' => $this->is_default,
            'is_enabled' => $this->is_enabled,
            'created_at' => $this->created_at->toDateTimeString(),
        ];

        if ($this->config) {
            $cfg = [
                'seo_keywords' => $this->config->seo_keywords,
                'seo_description' => $this->config->seo_description,
                'introduce' => $this->config->introduce,
                'banner_url' => $this->config->banner_url,
            ];
        } else {
            $cfg = [
                'seo_keywords' => '',
                'seo_description' => '',
                'introduce' => '',
                'banner_url' => '',
            ];
        }

        return array_merge($shop, $cfg);
    }
}
