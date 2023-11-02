<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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

        // Schema::table('provincias', function (Blueprint $table) {
        //     $table->timestamp('created_at')->useCurrent();
        //     $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        // });

        // Schema::table('municipios', function (Blueprint $table) {
        //     $table->timestamp('created_at')->useCurrent();
        //     $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        // });

        // Schema::table('sectors', function (Blueprint $table) {
        //     $table->timestamp('created_at')->useCurrent();
        //     $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        // });
    }
}