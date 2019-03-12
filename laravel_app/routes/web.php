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
Route::get('/', IndexController::class);
Route::get('/login', 'LoginController@show')->name('login');
Route::post('/login', 'LoginController@postLogin');
/* register page */
Route::get('/create_user', 'CreateUserController@index')->name('create_user');
Route::post('/create_user', 'CreateUserController@store')->name('create_user.store');

/* logout */
Route::get('/logout', LogoutController::class)->name('logout');

Route::middleware(['auth_user'])->group(function(){
    /* dashboard page */
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::put('/dashboard', 'DashboardController@update')->name('dashboard_update');
    Route::delete('/dashboard', 'DashboardController@delete')->name('dashboard_delete');
    Route::get('/packet_capture/{data_id}', 'PacketCaptureController@index')->name('packet_capture_index');
    Route::get('/packet_capture', 'PacketCaptureController@indexNew')->name('packet_capture_new');
});
