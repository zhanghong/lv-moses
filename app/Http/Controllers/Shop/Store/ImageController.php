<?php

namespace App\Http\Controllers\Shop\Store;

use App\Models\Base\Upload;
use App\Models\Shop\Shop;
use App\Models\Store\Store;
use App\Http\Requests\Store\ImageRequest;
use App\Http\Resources\Base\UploadResource;
use App\Http\Controllers\Shop\Controller;

class ImageController extends Controller
{
    public function index(Shop $shop, Store $store)
    {
        // $paginate = Store::where('shop_id', $shop->id)->paginate();
        // return ListResource::collection($paginate);
    }

    public function store(ImageRequest $request, Shop $shop)
    {
        $options = [
            'folder' => 'store',
            'max_width' => Store::IMAGE_WIDTH_INTRO,
        ];
        $image = Upload::saveAttach($shop, Store::UPLOAD_TYPE_INTRO, $request->image, $options);
        return new UploadResource($image);
    }

    public function destroy(Shop $shop, Upload $image)
    {
        $image->delete();
        return response()->json(null, 204);
    }
}
