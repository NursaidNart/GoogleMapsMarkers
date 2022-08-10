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
        if($user->permissionsCheck(['admin','create_user'])){
            return response()->json([
                'users' => User::get(),
            ], 200);
        }else{
            abort(403,'Permission denied');
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
        $error = false;
        if($user->permissionsCheck(['admin','create_user'])){
            $user_data = $request->all();
            $is_there_email = User::where('email',$user_data['email'])->first();
            if($is_there_email){
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
            abort(403,'Permission denied');
        }

        return response()->json([
            'error' => $error,
            'message' => $message,
        ], 200);
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
        if($user->permissionsCheck(['admin','create_user'])){
            return response()->json([
                'user' => User::where('id',$id)->first(),
            ], 200);
        }else{
            abort(403,'Permission denied');
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
        if($user->permissionsCheck(['admin','create_user'])){
            $user_data = $request->all();
            $update_user = User::where('id',$id)->first();
            if($update_user){
                $env_admin_email = env('ADMIN_EMAIL', '');
                if($update_user->email == $env_admin_email){
                    return response()->json([
                        'error' => true,
                        'message' => 'Admin data is not changeable',
                    ], 200);
                }
                if($user_data['email'] == $env_admin_email){
                    return response()->json([
                        'error' => true,
                        'message' => 'This email is not useable',
                    ], 200);
                }

                if($update_user->email != $user_data['email']){
                    $email_check = User::where('id','!=',$update_user->id)->where('email',$user_data['email'])->first();
                    if($email_check){
                        return response()->json([
                            'error' => true,
                            'message' => 'Email already exist',
                        ], 200);
                    }
                }

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

                return response()->json([
                    'error' => false,
                    'message' => 'Successfully saved',
                ], 200);
            }else{
                return response()->json([
                    'error' => false,
                    'message' => 'User is not exist',
                ], 200);
            }
        }else{
            abort(403,'Permission denied');
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
        if($user->permissionsCheck(['admin','create_user'])){

            $delete_user = User::where('id',$id)->first();
            if($delete_user->email == env('ADMIN_EMAIL', '')){
                return response()->json([
                    'error' => true,
                    'message' => 'Admin data is not changeable',
                ], 200);
            }
            $delete_user->delete();
            return response()->json([
                'error' => false,
                'message' => 'Successfully deleted',
            ], 200);

        }else{
            abort(403,'Permission denied');
        }
    }
}
