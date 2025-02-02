<?php

use Illuminate\Support\Facades\Route;

<<<<<<< HEAD
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

Route::get('/', function () {
    return view('welcome');
});
// Route::any('/admin/{any}', function () {
//     return view('admin');
// });
Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
Route::post('/users/logout', 'Auth\LoginController@userLogout')->name('user.logout');

Route::prefix('/admin')->group( function(){
    Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
    Route::get('', 'AdminController@index')->name('admin.dashboard');
    // // Route::any('/{any}', 'AdminController@index')->where('any', '.*')->middleware('auth');
    Route::post('/logout', 'Auth\AdminLoginController@adminLogout')->name('admin.logout');
    Route::get('/register', 'Auth\AdminRegisterController@showRegisterForm')->name('admin.register');
    Route::post('/register', 'Auth\AdminRegisterController@register')->name('admin.register.submit');
});
Route::get('/admininit', 'AdminController@admininit')->name('admininit');
Route::any('/admin/{slug}', function(){
    return view('admin')->middleware('auth:admin');
});
Route::get('/user/init', 'UserController@init')->name('init');
Route::get('/users/details', 'UserController@members')->name('members');

Route::get('/{catchall?}', function () {
    return view('welcome');
=======
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Route::get('/{any}', 'AppController@index')->where('any', '.*');
Route::get('/app', 'AppController@index')->name('home');
Route::post('/users/logout', 'Auth\LoginController@userLogout')->name('user.logout');
// Route::post('/user/register', 'Auth\RegisterController@register')->name('user.register');

Route::prefix('/admin')->group( function(){
    Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
    // Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
    Route::get('', 'AdminController@index')->name('admin.dashboard');
    // Route::post('/logout', 'Auth\AdminLoginController@adminLogout')->name('admin.logout');
    Route::get('/register', 'Auth\AdminRegisterController@showRegisterForm')->name('admin.register');
    Route::post('/register', 'Auth\AdminRegisterController@register')->name('admin.register.submit');
>>>>>>> 618d5a84e3460e9d830f42d69dd19295c6b2cbbd
});