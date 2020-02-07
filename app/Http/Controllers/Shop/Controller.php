<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller as HttpController;

class Controller extends HttpController
{
    protected function responseData($data, $code = 200)
    {
        return response()->json(['data' => $data], $code);
    }
}
