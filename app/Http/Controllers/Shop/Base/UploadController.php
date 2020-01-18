<?php

namespace App\Http\Controllers\Shop\Base;

use Illuminate\Http\Request;
use App\Models\Shop\Shop;
use App\Models\Base\Upload;
use App\Http\Resources\Base\UploadResource;
use App\Http\Controllers\Shop\Controller;

class UploadController extends Controller
{
    public function store(Request $request, Shop $shop)
    {
        $attach_type = $request->input('attach_type', 'demo');

        $options = [];
        $options['max_width'] = $request->input('max_width', 0);
        $is_bind = $request->input('is_bind', '');
        if ($is_bind) {
            $options['attachable'] = $shop;
        }

        $upload = Upload::saveAttach($shop, $attach_type, $request->file, $options);
        return new UploadResource($upload);
    }

    public function show(Shop $shop, Upload $upload)
    {
        return new UploadResource($upload);
    }
}
