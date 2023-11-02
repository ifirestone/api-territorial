<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        #Usuarios | Módulos | Permisos Base
        $this->Call(PermisoSeeder::class);
        $this->Call(ModuloSeeder::class);
        $this->Call(RoleSeeder::class);
        $this->Call(UserSeeder::class);
    }
}