<?php

namespace App\Http\Requests\Shop;

use App\Models\Shop\Shop;
use App\Http\Requests\FormRequest as BaseRequest;

class FormRequest extends BaseRequest
{
    public function shop()
    {
        return Shop::current();
    }

    public function currentShopId()
    {
        $shop = $this->shop();
        if ($shop) {
            return $shop->id;
        }
        return 0;
    }
}
