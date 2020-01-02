<?php

use Illuminate\Http\Request;
use \App\Laravue\Faker;
use \App\Laravue\JsonResponse;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'api'], function () {
    Route::post('auth/login', 'AuthController@login');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('auth/user', 'AuthController@user');
        Route::post('auth/logout', 'AuthController@logout');
    });

    Route::apiResource('users', 'User\UserController')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);
    Route::get('users/{user}/permissions', 'User\UserController@permissions')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);
    Route::put('users/{user}/permissions', 'User\UserController@updatePermissions')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);

    Route::apiResource('roles', 'Role\RoleController')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);
    Route::get('roles/{role}/permissions', 'Role\RoleController@permissions')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);
    Route::apiResource('permissions', 'Role\PermissionController')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);

    Route::apiResource('shops/{shop}/brands', 'Shop\BrandController');
});
