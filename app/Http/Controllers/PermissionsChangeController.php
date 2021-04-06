<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\UserPermissions;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as ModelsRole;

class PermissionsChangeController extends Controller
{
    //
    public function showAllRoles(){
            $roles = ModelsRole::all();
            return view('settings.permissionschange.allroles')->with('roles',$roles);
    }

    public function showRolePermissions($id){
        $role = ModelsRole::find($id);
        $role_permissions = ModelsRole::findByName($role->name)->permissions;
        $all_permissions = Permission::all();
        //print_r($all_permissions);
        return view('settings.permissionschange.rolepermissions')->with([
            'role' => $role,
            'role_permissions' => $role_permissions,
            'all_permissions' =>  $all_permissions,
        ]);
    }

    public function editPermissionsForRole(Request $request , $id){
        $role = ModelsRole::find($id);
        $permissions_add = $request->input('permissions'); // array of permissions that send in checkbox (yes)
        $all_permissions = Permission::all();
        //print_r($permissions);
        foreach($all_permissions as $per)
        {
            if(in_array($per->name , $permissions_add))
            {
                $role->givePermissionTo($per->name);
            }
            else{
                $role->revokePermissionTo($per->name);
            }
        }
        return back()->with('success','تم تعديل الصلاحيات بنجاح');
    }

    public function showAllUsersToEditPermissions(){
        $users = User::all();
        return view('settings.permissionschange.allusers')->with('users',$users);
    }

    public function showUserPermissions($id){
        $user = User::find($id);
        $user_permissions = $user->getAllPermissions();
        $all_permissions = Permission::all();
        return view('settings.permissionschange.userpermissions')->with([
            'user' => $user,
            'user_permissions' => $user_permissions,
            'all_permissions' =>  $all_permissions,
        ]);
    }

    public function editPermissionsForUser(Request $request , $id){
        $user = User::find($id);
        $permissions_add = $request->input('permissionsuser'); // array of permissions that send in checkbox (yes)
        $all_permissions = Permission::all();
        foreach($all_permissions as $per)
        {
            if(in_array($per->name , $permissions_add))
            {
                $user->givePermissionTo($per->name);
            }
            else{
                $user->revokePermissionTo($per->name);
            }
        }
        //$user->notify(new UserPermissions());
        return back()->with('success','تم تعديل الصلاحيات بنجاح');
    }

}
