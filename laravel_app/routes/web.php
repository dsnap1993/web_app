<?php

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

/* login page */
Route::get('/', IndexController::class)->name('index');
Route::get('/login', 'LoginController@show')->name('login');
Route::post('/login/post_login', 'LoginController@postLogin')->name('login');

Auth::routes();

/* dashboard page */
Route::get('/dashboard', 'DashboardController@show')->name('dashboard');
//Route::get('/create_user', CreateUserController::class)->name('create_user');
//Route::get('/capture_packet', CapturePacketController::class)->name('capture_packet');
