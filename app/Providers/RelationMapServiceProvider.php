<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class RelationMapServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'users' => 'App\Models\User\User',
            'shops' => 'App\Models\Shop\Shop',
            'shop_configs' => 'App\Models\Shop\Config',
            'stores' => 'App\Models\Store\Store',
            'products' => 'App\Models\Product\Product',
            'product_skus' => 'App\Models\Product\Sku',
        ]);
    }
}
