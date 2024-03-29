<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $path = public_path('data_analiticas.sql');
        $path = getcwd() . '/database/seeders/provincias.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);

        $path2 = getcwd() . '/database/seeders/municipios.sql';
        $sql2 = file_get_contents($path2);
        DB::unprepared($sql2);

        $path3 = getcwd() . '/database/seeders/sectores.sql';
        $sql3 = file_get_contents($path3);
        DB::unprepared($sql3);
    }
}
