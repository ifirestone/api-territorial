<?php

namespace Database\Seeders;

use App\Models\Modulo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modulo = new Modulo();
        $modulo->nombre = 'usuarios';
        $modulo->descripcion = 'Modulo Usuarios';
        $modulo->save();

        $modulo = new Modulo();
        $modulo->nombre = 'roles';
        $modulo->descripcion = 'Modulo Roles';
        $modulo->save();
    }
}