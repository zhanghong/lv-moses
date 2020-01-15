<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;

use App\Models\Shop\Shop;
use App\Models\Shop\Upload;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\ConfigRequest;
use App\Http\Requests\Shop\MainImageRequest;
use App\Http\Requests\Shop\BannerImageRequest;
use App\Http\Requests\Base\FieldUniqueRequest;
use App\Http\Resources\Shop\UploadResource;
use App\Http\Resources\Shop\ConfigResource;
use App\Exceptions\LogicException;

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

    // 验证店铺名称等字段值是否唯一
    public function unique(FieldUniqueRequest $request)
    {
        $id = $request->input('id');
        $name = $request->input('name', '');
        $value = $request->input('value', '');

        $wheres = [];
        if ($id > 0) {
           $wheres[] = ['id', '<>', $id];
        }
        try {
            $flag = Shop::checkAttrUnique($name, $value, $wheres);
        } catch (LogicException $e) {
            return $e->render();
        }

        return ['code' => 200, 'message' => '字段值唯一'];
    }

    // 上传Logo图片
    public function main_image(MainImageRequest $request, Shop $shop)
    {
        $options = [
            'folder' => 'shop',
            'max_width' => Shop::IMAGE_WIDTH_MAIN,
            'attachable' => $shop,
        ];
        $image = Upload::saveShopAttach($shop->id, Shop::UPLOAD_TYPE_MAIN_IMAGE, $request->image, $options);
        return new UploadResource($image);
    }

    // 上传Banner图片
    public function banner_image(BannerImageRequest $request, Shop $shop)
    {
        $options = [
            'folder' => 'shop',
            'max_width' => Shop::IMAGE_WIDTH_BANNER,
            'attachable' => $shop,
        ];
        $image = Upload::saveShopAttach($shop->id, Shop::UPLOAD_TYPE_BANNER, $request->image, $options);
        return new UploadResource($image);
    }
}
