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
Route::get('/', ActionIndex::class)->name('index');
Route::get('/login', ActionLogin::class)->name('login');
Route::get('/dashboard', ActionDashboard::class)->name('dashboard');
Route::get('/create_user', ActionCreateUser::class)->name('create_user');
Route::get('/capture_packet', ActionCapturePacket::class)->name('capture_packet');
