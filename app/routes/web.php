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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware('auth:web')
    ->group(function () {
        Route::get('/user', 'User\IndexController')->name('user.index');
        Route::get('/user/create', 'User\CreateController')->name('user.create');
        Route::post('/user', 'User\StoreController')->name('user.store');
    });
