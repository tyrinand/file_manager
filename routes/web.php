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

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/login','MyAvt@login')->name('login'); //маршрут для логина
Route::get('/login',function () {return view('welcome');})->name('login'); //маршрут для редиректа
Route::post('/logout','MyAvt@logout')->name('logout'); //маршрут для выхода
