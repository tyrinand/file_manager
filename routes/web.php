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
    if (!empty(Auth::user()))
        return redirect()->route('home');
    else
        return view('welcome');
});
//авторизация
Route::get('/home', 'HomeController@index')->name('home');
Route::post('/login','MyAvt@login')->name('login'); //маршрут для логина
Route::get('/login',function () {return view('welcome');})->name('login'); //маршрут для редиректа
Route::post('/logout','MyAvt@logout')->name('logout'); //маршрут для выхода
Route::get('/logout','MyAvt@logout')->name('logout');

//работа с папками
Route::get('/folder_create/{folder}','FolderController@create')->name('folder_create'); // форма создания
Route::post('/folder_store','FolderController@store')->name('folder_store');//сохранение папки
Route::delete('/delfolder/{folder}','FolderController@destroy')->name('folder_delete'); // удаление
Route::get('/folder_edit/{folder}','FolderController@edit')->name('folder_edit'); // форма редактирования
Route::patch('/folder_update/{folder}','FolderController@update')->name('folder_update'); //update folder

Route::get('/folder_child/{folder}','FolderController@inter_parent_out_child')->name('folder_child'); // получение потомком по родителю
Route::get('/folder_parent/{folder}','FolderController@inter_child_out_parent')->name('folder_parent'); // получение потомком по родителю
Route::get('/root_folder','FolderController@root_folder')->name('root_folder'); // домашний каталог

// работа с файлами 
Route::get('/file_upload/{folder}','FileController@uploadform')->name('file_upload'); // форма создания
Route::post('/file_upload_save','FileController@upload')->name('file_save'); // сохранение на сервере
Route::get('/file_share/{file}','FileController@share')->name('share'); // маршрут для формы отправки
Route::get('/file_download_master/{file}','FileController@master_download')->name('master_download'); // маршрут для загрузки внутри
Route::get('/file_close/{file}','FileController@close')->name('file_close'); // закрытие доступа
// загрузка извне
Route::get('/file_download_form/{file}','Download@form')->name('download_form'); // маршрут для формы
Route::post('/file_download_form_login','Download@form_login')->name('download_form_login'); // маршрут для формы
//удаление
Route::delete('/file_delete_basket/{file}','FileController@delete_basket')->name('delete_basket'); // удаление файла в корзину
Route::get('/file_basket/{folder}', 'FileController@basket_all')->name('basket');