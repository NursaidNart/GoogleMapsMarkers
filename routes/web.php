<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoogleMapsMarkersController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//main page redirect AuthController
Route::get('/', function () {
    return Redirect('login');
});

Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'login'])->name('login.post');

Route::group(['middleware' => ['auth']], function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/welcome',[DashboardController::class, 'welcome']);
    Route::resource('/user', UserController::class);
    Route::resource('/marker', GoogleMapsMarkersController::class);
    Route::get('/get_my_markers',[GoogleMapsMarkersController::class, 'getMyMarkers']);

    Route::any('/{any?}', function ($any) {
        return Redirect('welcome');
        //FIXME
    });
});
