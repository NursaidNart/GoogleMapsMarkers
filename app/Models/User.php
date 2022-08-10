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


    public function permissionsCheck($control_list){
        foreach($control_list as $control_key){
            if($control_key == 'admin'){
                if($this->role_type != 'admin'){
                    return false;
                }
            }else{
                if(!$this->permissionsKeyIsExists($control_key)){
                    return false;
                }
            }
        }
        return true;

    }

    public function permissionsKeyIsExists($key){
        if(is_array($this->permissions)){
            return in_array($key,$this->permissions);
        }else{
            return false;
        }
    }

    public function getDashboardData($user){
        $permissions_list = config('global.permissions');

        $routes = [];
        foreach($permissions_list as $route=>$title){
            if($user->permissionsKeyIsExists($route)){
                $routes[] = [
                    'key' => $route,
                    'title' => $title,
                ];
            }
        }

        $users = [];
        $roles = [];
        if($user->permissionsCheck(['admin','create_user'])){
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
