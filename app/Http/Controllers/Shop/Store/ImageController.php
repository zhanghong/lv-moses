<?php

namespace App\Http\Controllers\Shop\Store;

use App\Models\Base\Upload;
use App\Models\Shop\Shop;
use App\Models\Store\Store;
use App\Http\Requests\Shop\Store\ImageRequest;
use App\Http\Resources\Base\UploadResource;
use App\Http\Controllers\Shop\Controller;

class ImageController extends Controller
{
    public function index(Store $store)
    {
        // $paginate = Store::where('shop_id', $this->shop->id)->paginate();
        // return ListResource::collection($paginate);
    }

    public function store(ImageRequest $request)
    {
        $options = [
            'folder' => 'store',
            'max_width' => Store::IMAGE_WIDTH_INTRO,
        ];
        $image = Upload::saveAttach($this->shop, Store::UPLOAD_TYPE_INTRO, $request->image, $options);
        return new UploadResource($image);
    }

    public function destroy(Upload $image)
    {
        $image->delete();
        return response()->json(null, 204);
    }
}
