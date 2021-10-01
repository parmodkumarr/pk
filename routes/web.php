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

Route::get('/', function () {
    return view('welcome');
})->middleware('admin') ;

Route::get('/adminlogin', function () {
    return view('auth.login');
})->name('adminlogin');

Auth::routes();

Route::group(['middleware' =>'admin','prefix' => 'admin','namespace' => 'Admin','as' => 'admin.'], function () {
    Route::get('/','HomeController@index')->name('dashboard');
    Route::get('/profile','HomeController@profile')->name('profile');
    Route::post('/update_profile','HomeController@profileUpdate')->name('profileUpdate');
    Route::resource('categories', 'CategoryController');
    Route::resource('users', 'UserController');
});