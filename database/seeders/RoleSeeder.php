<?php

namespace Database\Seeders;

use App\Models\Modulo;
use App\Models\Permiso;
use App\Models\Role;
use App\Models\RoleModulo;
use App\Models\RolePermiso;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->nombre = "System Administrator";
        $role->descripcion = "Administrador del Sistema";
        $role->save();

        foreach (Permiso::all() as $permiso) {
            $rp = new RolePermiso();
            $rp->role_id = $role->id;
            $rp->permiso_id = $permiso->id;
            $rp->save();
        }

        foreach (Modulo::all() as $modulo) {
            $rm = new RoleModulo();
            $rm->role_id = $role->id;
            $rm->modulo_id = $modulo->id;
            $rm->save();
        }
    }
}