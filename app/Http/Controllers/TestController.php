<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PerformersP1;
use App\Models\PerformersP1IDS;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TestController extends BaseController
{
    public function permisos()
    {
        $sa = Role::create(['name' => 'SuperAdmin', 'guard_name' => 'api']);

        $permisos = [];

        $permisos[] = Permission::create(['name' => 'nombre_del_permiso', 'guard_name' => 'api']);
        $permisos[] = Permission::create(['name' => 'nombre_del_permiso 2', 'guard_name' => 'api']);

        foreach ($permisos as $permiso) {
            $sa->givePermissionTo($permiso);
        }
        echo "Permisos creados\n";
    }

    public function permisos_client()
    {
        $cl = Role::create(['name' => 'Client', 'guard_name' => 'api']);
        $permisos = [];
        $permisos[] = Permission::create(['name' => 'nombre_del_permiso', 'guard_name' => 'api']);
        foreach ($permisos as $permiso) {
            $cl->givePermissionTo($permiso);
        }
    }

    public function rol_to_user($id)
    {
        $user = User::find($id);
        $user->assignRole('SuperAdmin');
        echo "Rol asignado";
    }

    public function new_permission_to_role()
    {
        $role       = Role::findByName('SuperAdmin', 'api');
        $permisos   = [];
        $permisos[] = Permission::create(['name'       => 'nombre_del_permiso',
                                          'guard_name' => 'api']);
        foreach ($permisos as $permiso) {
            $role->givePermissionTo($permiso);
            echo "rol" . $role->name . " ya tiene permiso para " . $permiso->name;
        }
    }
}
