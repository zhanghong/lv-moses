<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Shop\Shop;
use App\Models\Shop\Upload;
use App\Http\Resources\Shop\UploadResource;


class UploadController extends Controller
{
    public function store(Request $request, Shop $shop)
    {
        $attach_type = $request->input('attach_type', 'demo');

        $options = [];
        $options['attachable_type'] = $request->input('attachable_type', '');
        $options['attachable_id'] = $request->input('attachable_id', 0);
        $options['max_width'] = $request->input('max_width', 0);

        $upload = Upload::saveShopAttach($shop->id, $attach_type, $request->file, $options);
        return new UploadResource($upload);
    }

    public function show(Shop $shop, Upload $upload)
    {
        return new UploadResource($upload);
    }
}
