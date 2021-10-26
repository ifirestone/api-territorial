<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        #Territorial
        $this->Call(CountrySeeder::class);
        $this->Call(ProvinciasSeeder::class);
        $this->Call(MunicipiosSeeder::class);
        $this->Call(DistritosSeeder::class);

    }
}