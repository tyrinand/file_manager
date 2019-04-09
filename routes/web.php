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
    /*if (!empty(Auth::user()))
        return redirect()->route('home');
    else
    */
        return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/login','MyAvt@login')->name('login'); //маршрут для логина
Route::get('/login',function () {return view('welcome');})->name('login'); //маршрут для редиректа
Route::post('/logout','MyAvt@logout')->name('logout'); //маршрут для выхода
//авторизация

Route::get('/folder_create/{folder}','FolderController@create')->name('folder_create'); // форма создания
Route::post('/folder_store','FolderController@store')->name('folder_store');//сохранение папки
Route::delete('/delfolder/{folder}','FolderController@destroy')->name('folder_delete'); // удаление

Route::get('/folder_child/{folder}','FolderController@inter_parent_out_child')->name('folder_child'); // получение потомком по родителю