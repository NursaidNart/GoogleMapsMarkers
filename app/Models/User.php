<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'permissions' => 'array',
    ];


    public function getPermissions($user){
        return $user->permissions;
    }

    public function getDashboardData($user){
        $is_admin = $user->role_type == 'admin'?1:0;
        $user_permissions = $this->getPermissions($user);
        $permissions_list = config('global.permissions');

        $routes = [];
        if($is_admin){
            if($user_permissions){
                foreach($permissions_list as $route=>$title){
                    if(in_array($route,$user_permissions)){
                        $routes[] = [
                            'key' => $route,
                            'title' => $title,
                        ];
                    }
                }
            }
        }

        $users = [];
        if($is_admin && in_array('create_user',$user_permissions)){
            $users = User::get();
            $roles = config('global.roles');
        }

        $data = [
            'routes' => $routes,
            'roles' => $roles,
            'users' => $users,
            'user' => $user,
            'permissions' => $permissions_list,
        ];

        return $data;
    }
}
