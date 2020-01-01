<?php
namespace App\Http\Controllers\Role;

use Illuminate\Http\Request;
use App\Models\Role\Role;
use App\Models\Role\Permission;
use App\Http\Resources\Role\RoleResource;
use App\Http\Resources\Role\PermissionResource;
use App\Http\Controllers\Controller as BaseController;

class RoleController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return RoleResource::collection(Role::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  Role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        if ($role === null || $role->isAdmin()) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        $permissionIds = $request->get('permissions', []);
        $permissions = Permission::allowed()->whereIn('id', $permissionIds)->get();
        $role->syncPermissions($permissions);
        $role->save();
        return new RoleResource($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Get permissions from role
     *
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function permissions(Role $role)
    {
        return PermissionResource::collection($role->permissions);
    }
}
