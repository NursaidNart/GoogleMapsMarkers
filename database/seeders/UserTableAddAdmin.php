<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableAddAdmin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(env('ADMIN_EMAIL', '') == ''  || env('ADMIN_NAME', '') == ''  || env('ADMIN_PASSWORD', '') == '' ){
            dd('email or name or password not defined');
        }
        // if not exist create admin user
        User::updateOrCreate(
            ['email' =>  env('ADMIN_EMAIL', ''),], [
                'name' =>  env('ADMIN_NAME', ''),
                'password' => Hash::make(env('ADMIN_PASSWORD', '')),
                'role_type' => 'admin',
                'permissions' => ['create_user','create_google_maps_markers','show_my_markers'],
            ]
        );
    }
}
