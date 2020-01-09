<?php

namespace App\Http\Controllers\Shop;

use App\Models\Shop\Shop;
use App\Http\Controllers\Controller;
use App\Http\Resources\Shop\ConfigResource;

class ConfigController extends Controller
{
    public function show(Shop $shop)
    {
        return new ConfigResource($shop);
    }

    public function update(ConfigRequest $request, Shop $shop)
    {

        $shop->updateInfo($request->all());
        return new ConfigResource($shop);
    }
}
