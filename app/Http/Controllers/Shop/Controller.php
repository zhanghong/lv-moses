<?php

namespace App\Http\Controllers\Shop;

use App\Models\Shop\Shop;
use App\Http\Controllers\Controller as HttpController;

class Controller extends HttpController
{
    protected $shop;

    public function __construct()
    {
        $this->shop    = Shop::current();
    }
}
