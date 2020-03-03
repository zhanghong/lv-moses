<?php

use Illuminate\Http\Request;
use \App\Laravue\Faker;
use \App\Laravue\JsonResponse;

Route::group(['middleware' => 'api'], function () {
    Route::middleware('throttle:' . config('api.rate_limits.sign'))->group(function () {
        Route::post('auth/login', 'AuthController@login');
    });

    Route::middleware('throttle:' . config('api.rate_limits.access'))->group(function () {
        // 登录后可以访问的接口
        Route::group(['middleware' => 'auth:api'], function () {
            // 查看当前登录用户
            Route::get('user', 'AuthController@user');
            Route::get('auth/user', 'AuthController@user');
            Route::post('auth/logout', 'AuthController@logout');
        });

        Route::get('areas/districts', 'Common\AreaController@districts');
        Route::get('areas/streets', 'Common\AreaController@streets');
        Route::get('areas/{area}', 'Common\AreaController@list');

        Route::apiResource('users', 'User\UserController');
        Route::get('users/{user}/permissions', 'User\UserController@permissions');
        Route::put('users/{user}/permissions', 'User\UserController@updatePermissions');

        Route::apiResource('roles', 'Role\RoleController');
        Route::get('roles/{role}/permissions', 'Role\RoleController@permissions');
        Route::apiResource('permissions', 'Role\PermissionController');

        Route::group(['prefix' => 'shop', 'middleware' => 'check_shop'], function() {
            Route::patch('base/index', 'Shop\Base\IndexController@update');
            Route::get('base/index', 'Shop\Base\IndexController@index');
            Route::post('base/index/main_image', 'Shop\Base\IndexController@main_image');
            Route::post('base/index/banner_image', 'Shop\Base\IndexController@banner_image');
            Route::post('base/index/unique', 'Shop\Base\IndexController@unique');

            Route::post('base/uploads', 'Shop\Base\UploadController@store');
            Route::get('base/uploads/{upload}', 'Shop\Base\UploadController@show');

            Route::post('store/index/unique', 'Shop\Store\IndexController@unique');
            Route::apiResource('store/index', 'Shop\Store\IndexController', ['parameters' => ['index' => 'store']]);
            // 门店图片
            Route::get('store/image/{store}', 'Shop\Store\ImageController@index');
            Route::post('store/image', 'Shop\Store\ImageController@store');
            Route::delete('store/image/{image}', 'Shop\Store\ImageController@destroy');

            Route::post('store/agents/unique', 'Shop\Store\AgentController@unique');
            Route::get('store/agents/list', 'Shop\Store\AgentController@list');
            Route::apiResource('store/agents', 'Shop\Store\AgentController');

            // 基础类目
            Route::apiResource('category/index', 'Shop\Category\IndexController', ['parameters' => ['index' => 'category']])->only(['index', 'show']);

            // 商品模块
            // 商品分类
            Route::apiResource('product/groups', 'Shop\Product\GroupController');
            Route::post('product/groups/unique', 'Shop\Product\GroupController@unique');
            // 商品品牌
            Route::apiResource('product/brands', 'Shop\Product\BrandController');
            Route::post('product/brands/unique', 'Shop\Product\BrandController@unique');
            // 商品管理
            Route::apiResource('product/index', 'Shop\Product\IndexController', ['parameters' => ['index' => 'id']]);
            Route::post('product/index/form', 'Shop\Product\IndexController@form');
            Route::get('product/category', 'Shop\Product\IndexController@category');
            // 商品规格
            Route::apiResource('product/standards', 'Shop\Product\StandardController', ['parameters' => ['standards' => 'id']]);
            Route::post('product/standards/unique', 'Shop\Product\StandardController@unique');
            // 商品图片
            Route::post('product/images/main', 'Shop\Product\ImageController@main');
            Route::post('product/images/desc', 'Shop\Product\ImageController@desc');
        });
    });
});
