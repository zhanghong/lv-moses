<?php

namespace App\Http\Controllers\Shop\Product;

use App\Models\Shop\Shop;
use App\Models\Base\Upload;
use App\Models\Product\Product;
use App\Http\Controllers\Shop\Controller;
use App\Http\Resources\Base\UploadResource;
use App\Http\Requests\Product\MainImageRequest;
use App\Http\Requests\Product\DescImageRequest;

class ImageController extends Controller
{
    public function main(MainImageRequest $request, Shop $shop)
    {
        $options = [
            'forder' => 'product',
        ];

        $image = Upload::saveAttach($shop, Product::UPLOAD_TYPE_IMAGE_MAIN, $request->image, $options);
        return new UploadResource($image);
    }

    public function desc(DescImageRequest $request, Shop $shop)
    {
        $options = [
            'forder' => 'product',
        ];

        $image = Upload::saveAttach($shop, Product::UPLOAD_TYPE_IMAGE_DESC, $request->image, $options);
        return new UploadResource($image);
    }
}
