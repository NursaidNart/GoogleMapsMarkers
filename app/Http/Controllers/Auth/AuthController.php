<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class AuthController extends Controller
{
     /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        // if user is logged redirect main page
        if(!Auth::check()){
            return view('auth.login');
        }else{
            return redirect('welcome');
        }
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        //verify if email and password is correct redirect to home page
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('welcome');
        }

        return redirect("login");
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function logout() {

        Session::flush();
        Auth::logout();

        return Redirect('login');
    }

}
