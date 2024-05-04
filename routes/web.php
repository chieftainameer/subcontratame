<?php

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
Route::group(['prefix'=>''], function($router) {
    $router->get('/', [App\Http\Controllers\HomeController::class, 'home'])->name('home');

    $router->group(['prefix'=>'auth'], function() use($router){
       $router->get('/login',[App\Http\Controllers\AuthController::class,'login'])->name('login');
       $router->post('/login',[App\Http\Controllers\AuthController::class,'login']);
       $router->get('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register');
       $router->post('/register', [App\Http\Controllers\AuthController::class, 'register']);
       $router->get('/reset-password', [App\Http\Controllers\AuthController::class, 'resetPassword']);
       $router->post('/reset-password', [App\Http\Controllers\AuthController::class, 'resetPassword']);
    });

    // Routes Search
    include('search.php');

    // Routes Regular User
    include('client.php');

    // Routes Administrator
    include('dashboard.php');
});