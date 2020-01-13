<?php

namespace App\Http\Controllers\Shop;

use App\Models\Shop\Shop;
use App\Models\Shop\Upload;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\ConfigRequest;
use App\Http\Requests\Shop\MainImageRequest;
use App\Http\Requests\Shop\BannerImageRequest;
use App\Http\Resources\Shop\UploadResource;
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

    public function main_image(MainImageRequest $request, Shop $shop)
    {
        $options = [
            'folder' => 'shop',
            'max_width' => Shop::IMAGE_WIDTH_MAIN,
            'attachable' => $shop,
        ];
        $image = Upload::saveShopAttach($shop->id, Shop::UPLOAD_TYPE_MAIN_IMAGE, $request->file, $options);
        return new UploadResource($image);
    }

    public function banner_image(BannerImageRequest $request, Shop $shop)
    {
        $options = [
            'folder' => 'shop',
            'max_width' => Shop::IMAGE_WIDTH_BANNER,
            'attachable' => $shop,
        ];
        $image = Upload::saveShopAttach($shop->id, Shop::UPLOAD_TYPE_BANNER, $request->file, $options);
        return new UploadResource($image);
    }
}
