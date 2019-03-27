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
/* forgot password */
Route::get('/forgot_password', 'ForgotPasswdController@index')->name('forgot_password');
Route::post('/forgot_password', 'ForgotPasswdController@sendEmail')->name('send_email');
/* logout */
Route::get('/logout', LogoutController::class)->name('logout');

Route::middleware(['auth_user'])->group(function(){
    /* dashboard page */
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('/dashboard/update', 'DashboardController@update')->name('dashboard_update');
    Route::put('/dashboard/update', 'DashboardController@update')->name('dashboard_update');
    Route::get('/dashboard/delete', 'DashboardController@delete')->name('dashboard_delete');
    Route::delete('/dashboard/delete', 'DashboardController@delete')->name('dashboard_delete');
    /* packet capture page */
    Route::get('/packet_capture/{data_id}', 'PacketCaptureController@index')->name('packet_capture_index');
    Route::get('/packet_capture', 'PacketCaptureController@indexNew')->name('packet_capture_new');
    Route::get('/packet_capture/create', 'PacketCaptureController@store')->name('capture_data_create');
    Route::post('/packet_capture/create', 'PacketCaptureController@store')->name('capture_data_create');
    /* profile page */
    Route::get('/profile', 'ProfileController@index')->name('profile');
    Route::get('/profile/edit', 'ProfileController@update')->name('profile_update');
    Route::put('/profile/edit', 'ProfileController@update')->name('profile_update');
    /* change password page */
    Route::get('/change_password', 'ChangePasswdController@index')->name('change_password');
    Route::get('/change_password/edit', 'ChangePasswdController@update')->name('change_password_update');
    Route::put('/change_password/edit', 'ChangePasswdController@update')->name('change_password_update');
});
