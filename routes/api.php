<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Admin;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::get('/', 'App\Http\Controllers\HomeController@home');
Route::get('/register', 'App\Http\Controllers\Auth\AuthController@RegistrationForm');
Route::post('/register', 'App\Http\Controllers\Auth\AuthController@Register');
Route::get('/login', 'App\Http\Controllers\Auth\AuthController@LoginForm');
Route::post('/login', 'App\Http\Controllers\Auth\AuthController@Login');
Route::get('/logout', 'App\Http\Controllers\Auth\AuthController@logout');
Route::middleware([Admin::class])->group(function(){
    Route::post('/AdminRegister', 'App\Http\Controllers\Auth\AuthController@CreateAdmin');
    Route::post('/CreateTrip', 'App\Http\Controllers\AdminController@CreateTrip');
});