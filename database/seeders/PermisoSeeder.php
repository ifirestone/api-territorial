<?php

namespace Database\Seeders;

use App\Models\Permiso;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permiso = new Permiso();
        $permiso->nombre = 'read';
        $permiso->descripcion = 'Leer';
        $permiso->save();

        $permiso = new Permiso();
        $permiso->nombre = 'create';
        $permiso->descripcion = 'Crear';
        $permiso->save();

        $permiso = new Permiso();
        $permiso->nombre = 'update';
        $permiso->descripcion = 'Actualizar';
        $permiso->save();

        $permiso = new Permiso();
        $permiso->nombre = 'destroy';
        $permiso->descripcion = 'Borrar';
        $permiso->save();
    }
}