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



        Route::patch('shops/{shop}/base/index', 'Shop\Base\IndexController@update');
        Route::get('shops/{shop}/base/index', 'Shop\Base\IndexController@index');
        Route::post('shops/{shop}/base/index/main_image', 'Shop\Base\IndexController@main_image');
        Route::post('shops/{shop}/base/index/banner_image', 'Shop\Base\IndexController@banner_image');
        Route::post('shops/base/index/unique', 'Shop\Base\IndexController@unique');

        Route::post('shops/{shop}/base/uploads', 'Shop\Base\UploadController@store');
        Route::get('shops/{shop}/base/uploads/{upload}', 'Shop\Base\UploadController@show');

        Route::post('shops/{shop}/store/index/unique', 'Shop\Store\AgentController@unique');
        Route::apiResource('shops/{shop}/store/index', 'Shop\Store\IndexController', ['parameters' => ['index' => 'store']]);
        // 门店图片
        Route::get('shops/{shop}/store/image/{store}', 'Shop\Store\ImageController@index');
        Route::post('shops/{shop}/store/image', 'Shop\Store\ImageController@store');
        Route::delete('shops/{shop}/store/image/{image}', 'Shop\Store\ImageController@destroy');

        Route::post('shops/{shop}/store/agents/unique', 'Shop\Store\AgentController@unique');
        Route::get('shops/{shop}/store/agents/list', 'Shop\Store\AgentController@list');
        Route::apiResource('shops/{shop}/store/agents', 'Shop\Store\AgentController');

        // 基础类目
        Route::apiResource('shops/{shop}/category/index', 'Shop\Category\IndexController', ['parameters' => ['index' => 'category']])->only(['index', 'show']);

        // 商品模块
        // 商品分类
        Route::apiResource('shops/{shop}/product/categories', 'Shop\Product\CategoryController');
        // 商品品牌
        Route::apiResource('shops/{shop}/product/brands', 'Shop\Product\BrandController');
        // 商品管理
        Route::apiResource('shops/{shop}/product/index', 'Shop\Product\IndexController', ['parameters' => ['index' => 'product']]);
        // 商品规格
        Route::post('shops/{shop}/product/standards', 'Shop\Product\StandardController@store');
        Route::post('shops/{shop}/product/standard_values/{property}', 'Shop\Product\StandardValueController@store');
        // 商品参数
        Route::post('shops/{shop}/product/params', 'Shop\Product\ParamController@store');
        Route::post('shops/{shop}/product/param_values/{property}', 'Shop\Product\ParamValueController@store');
    });
});
