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
        $env_admin_email = env('ADMIN_EMAIL', '');
        $env_admin_name = env('ADMIN_NAME', '');
        $env_admin_password = env('ADMIN_PASSWORD', '');

        if($env_admin_email == ''  || $env_admin_name == ''  || $env_admin_password == '' ){
            dd('email or name or password not defined');
        }
        // if not exist create admin user
        User::updateOrCreate(
            ['email' =>  $env_admin_email,], [
                'name' =>  $env_admin_name,
                'password' => Hash::make($env_admin_password),
                'role_type' => 'admin',
                'permissions' => ['create_user','create_google_maps_markers','show_my_markers'],
            ]
        );
    }
}
