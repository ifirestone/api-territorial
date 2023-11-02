<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->role_id = 1; // Full Admin
        $user->email = env('DEFAULT_USER_EMAIL');
        $user->username = env('DEFAULT_USERNAME');
        $user->email_verified_at = Carbon::now();
        $user->password = bcrypt(env('DEFAULT_USER_PASS'));
        $user->save();

        $profile = new Profile();
        $profile->user_id = $user->id;
        $profile->nombre = env('DEFAULT_USER_REAL_NAME');
        $profile->telefono = '555-555-5555';
        $profile->movil = '666-666-6666';
        $profile->save();
    }
}