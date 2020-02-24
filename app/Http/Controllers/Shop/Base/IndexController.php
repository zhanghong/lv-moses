<?php

namespace App\Http\Controllers\Shop\Base;

use App\Models\Shop\Shop;
use App\Models\Base\Upload;
use App\Http\Requests\Shop\Base\ConfigRequest;
use App\Http\Requests\Shop\Base\MainImageRequest;
use App\Http\Requests\Shop\Base\BannerImageRequest;
use App\Http\Requests\Base\FieldUniqueRequest;
use App\Http\Resources\Base\UploadResource;
use App\Http\Resources\Shop\ConfigResource;
use App\Exceptions\LogicException;
use App\Http\Controllers\Shop\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return new ConfigResource($this->shop);
    }

    public function update(ConfigRequest $request)
    {
        $this->shop->updateInfo($request->all());
        // 更新缓存数据
        Shop::cacheOrFindCurrent(true);
        return new ConfigResource($this->shop);
    }

    // 验证店铺名称等字段值是否唯一
    public function unique(FieldUniqueRequest $request)
    {
        $id = $this->shop->id;
        $name = $request->input('name', '');
        $value = $request->input('value', '');

        $wheres = [];
        if ($id > 0) {
           $wheres[] = ['id', '<>', $id];
        }
        try {
            $flag = Shop::checkAttrUnique($name, $value, $wheres);
        } catch (LogicException $e) {
            return $e;
        }

        return ['code' => 200, 'message' => '字段值唯一'];
    }

    // 上传Logo图片
    public function main_image(MainImageRequest $request)
    {
        $options = [
            'folder' => 'shop',
            'max_width' => Shop::IMAGE_WIDTH_MAIN,
        ];
        $image = Upload::saveAttach($this->shop, Shop::UPLOAD_TYPE_MAIN_IMAGE, $request->image, $options);
        return new UploadResource($image);
    }

    // 上传Banner图片
    public function banner_image(BannerImageRequest $request)
    {
        $options = [
            'folder' => 'shop',
            'max_width' => Shop::IMAGE_WIDTH_BANNER,
        ];
        $image = Upload::saveAttach($this->shop, Shop::UPLOAD_TYPE_BANNER, $request->image, $options);
        return new UploadResource($image);
    }
}
