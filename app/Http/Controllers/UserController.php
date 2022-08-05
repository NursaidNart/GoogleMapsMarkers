<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $is_admin = $user->role_type == 'admin'?1:0;
        $user_permissions = $user->getPermissions($user);
        $users = null;
        if ($is_admin){
            if($user_permissions
            && is_array($user_permissions)
            && in_array('create_user',$user_permissions)){
                $users = User::get();
            }
        }
        if(is_null($users)){
            abort(403, 'Access denied');
        }else{
            return response()->json([
                'users' => $users,
            ], 200);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $request->validate([
            'email' => 'required',
            'password' => 'required',
            'name' => 'required'
        ]);

        $user = Auth::user();
        $is_admin = $user->role_type == 'admin'?1:0;
        $user_permissions = $user->getPermissions($user);
        $error = false;
        $abort = false;
        if ($is_admin){
            if($user_permissions
                && is_array($user_permissions)
                && in_array('create_user',$user_permissions)){
                $user_data = $request->all();
                $new_user = User::where('email',$user_data['email'])->first();
                if($new_user){
                    $error = true;
                    $message = 'Email already exist';
                }else{

                    $new_user = new User();
                    $new_user->email = $user_data['email'];
                    $new_user->name = $user_data['name'];
                    if(isset($user_data['role_type'])){
                        $new_user->role_type = $user_data['role_type'];
                    }
                    if(isset($user_data['permissions'])){
                        $new_user->role_type = $user_data['permissions'];
                    }
                    if($user_data['password']){
                        $new_user->password = Hash::make($user_data['password']);
                    }
                    $new_user->saveOrFail();
                    $error = false;
                    $message = 'Successfully created';

                }

            }else{
                $abort = true;
                $message = 'Permission denied';
            }
        }else{
            $abort = true;
            $message = 'Permission denied';
        }
        if($abort){
            abort(403, $message);
        }else{
            return response()->json([
                'error' => $error,
                'message' => $message,
            ], 200);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $is_admin = $user->role_type == 'admin'?1:0;
        $user_permissions = $user->getPermissions($user);
        $result_user = null;
        if ($is_admin){
            if($user_permissions
            && is_array($user_permissions)
            && in_array('create_user',$user_permissions)){
                $result_user = User::where('id',$id)->first();
            }
        }
        if(is_null($result_user)){
            abort(403, 'Access denied');
        }else{
            return response()->json([
                'user' => $result_user,
            ], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'email' => 'required',
            'name' => 'required'
        ]);

        $user = Auth::user();
        $is_admin = $user->role_type == 'admin'?1:0;
        $user_permissions = $user->getPermissions($user);
        $error = false;
        $abort = false;
        if ($is_admin){
            if($user_permissions
            && is_array($user_permissions)
            && in_array('create_user',$user_permissions)){
                $user_data = $request->all();
                $update_user = User::where('id',$id)->first();
                if($update_user){
                    if($update_user->email != $user_data['email']){
                        $email_check = User::where('id','!=',$update_user->id)->where('email',$user_data['email'])->first();
                        if($email_check){
                            $error = true;
                            $message = 'Email already exist';
                        }
                    }

                    if($update_user->email == env('ADMIN_EMAIL', '')){
                        $error = true;
                        $message = 'Admin data is not changeable';
                    }

                    if (!$error){
                        $update_user->email = $user_data['email'];
                        $update_user->name = $user_data['name'];
                        if(isset($user_data['role_type'])){
                            $update_user->role_type = $user_data['role_type'];
                        }
                        if(isset($user_data['permissions'])){
                            $update_user->permissions = $user_data['permissions'];
                        }
                        if(isset($user_data['password'])){
                            $update_user->password = Hash::make($user_data['password']);
                        }
                        $update_user->saveOrFail();
                        $error = false;
                        $message = 'Successfully saved';
                    }

                }
            }else{
                $abort = true;
                $message = 'Permission denied';
            }
        }else{
            $abort = true;
            $message = 'Permission denied';
        }
        if($abort){
            abort(403, $message);
        }else{
            return response()->json([
                'error' => $error,
                'message' => $message,
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $is_admin = $user->role_type == 'admin'?1:0;
        $user_permissions = $user->getPermissions($user);
        $error = false;
        $abort = false;
        if ($is_admin){
            if($user_permissions
                && is_array($user_permissions)
                && in_array('create_user',$user_permissions)){

                $delete_user = User::where('id',$id)->first();
                if($delete_user->email == env('ADMIN_EMAIL', '')){
                    $error = true;
                    $message = 'Admin data is not changeable';
                }
                if(!$error){

                    $delete_user->delete();
                    $error = false;
                    $message = 'Successfully deleted';
                }


            }else{
                $abort = true;
                $message = 'Permission denied';
            }
        }else{
            $abort = true;
            $message = 'Permission denied';
        }
        if($abort){
            abort(403, $message);
        }else{
            return response()->json([
                'error' => $error,
                'message' => $message,
            ], 200);
        }
    }
}
