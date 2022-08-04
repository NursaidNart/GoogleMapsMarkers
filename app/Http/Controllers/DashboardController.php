<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
       /**
     * Show the profile for a given user.
     *
     * @return \Illuminate\View\View
     */
    public function welcome()
    {
        $user = Auth::user();
        $data = $user->getDashboardData($user);
        return view('welcome', ['data' => $data]);
    }

}
