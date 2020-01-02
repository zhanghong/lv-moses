<?php

namespace App\Observers\Shop;

use App\Models\Shop\Brand;
use App\Observers\BaseObserver;

class BrandObserver extends BaseObserver
{
    public function creating(Brand $brand)
    {
        $brand->editor()->associate($this->currentUser());
    }
}
