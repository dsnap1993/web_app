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
Route::get('/', IndexAction::class)->name('index');
Route::get('/login', 'LoginAction@show')->name('login');
Route::post('/login/post_login', 'LoginAction@postLogin')->name('login');
//Route::get('/dashboard', DashboardAction::class)->name('dashboard');
//Route::get('/create_user', CreateUserAction::class)->name('create_user');
//Route::get('/capture_packet', CapturePacketAction::class)->name('capture_packet');
