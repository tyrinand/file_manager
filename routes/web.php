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
        if(Auth::user()->login === 'SuperUser')
            return redirect()->route('admin_panel');
        else
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
Route::get('/file_basket', 'FileController@basket_all')->name('basket'); // отображение файлов в корзине
Route::get('/file_restore/{file}','FileController@rest')->name('rest'); // восстановление файла
Route::delete('/file_clear','FileController@clear')->name('file_clear'); // удаление навсегда
Route::delete('/clear_basket','FileController@clear_basket')->name('clear_basket'); // удаление навсегда
Route::delete('/all_input_basket','FileController@all_input_basket')->name('all_input'); // удаление навсегда
// админ панель
Route::get('/admin_panel', 'AdminControl@index')->name('admin_panel');
Route::get('/admin_panel/change_pass', 'AdminControl@password_change')->name('admin_password');//форма для смены пароля
Route::patch('/admin_panel/password_update','AdminControl@update_pass')->name('pass_update'); //update password
Route::get('/admin_panel/block_user/{User}','AdminControl@block_user')->name('block_user'); // блокировка пользователя
Route::get('/admin_panel/user_size_form/{User}','AdminControl@user_size_form')->name('user_size_form'); //форма для измения размера
Route::patch('/admin_panel/size_update','AdminControl@size_update')->name('size_update'); //update size


// полное удаление
Route::get('/admin_panel/delete_user_file/{User}','AdminControl@admin_delete_all')->name('admin_delete_all'); // блокировка пользователя
// маршруты удаления папок и файлов
Route::delete('/admin_panel/delete_user_file/{file}','AdminControl@admin_delete_file')->name('admin_delete_file'); // удаление файла пользователя
Route::delete('/admin_panel/block_user/{User}','AdminControl@admin_block_user')->name('admin_block_user'); // блокирование пользователя
Route::delete('/admin_panel/delete_user_folder/{folder}','AdminControl@admin_delete_folder')->name('admin_delete_folder');
//поиск
Route::post('/serch','FolderController@serch_str')->name('serch_str');
Route::post('admin_panel/serch_login','AdminControl@serch_login')->name('serch_login');

// работа с группами
Route::get('/user_group', 'GroupController@index')->name('user_group'); // главное окно с 3 вкладками
Route::get('/user_group/group_create','GroupController@create')->name('group_create');
Route::post('/user_group/group_store','GroupController@store')->name('group_store');//сохранение папки
Route::get('/user_group/group_share/{group}','GroupController@share')->name('group_share'); // поделиться группой
//подписчика
Route::get('/user_group/group_sub/{group}','Download@form_sub')->name('group_sub_form'); // 
Route::post('/user_group/group_sub_form','Download@group_login_sub')->name('group_login_sub'); // маршрут для формы
//публичные папки
Route::get('/folder_list_group/{folder}','FolderController@list_group')->name('folder_list_group'); //вывод списка групп
Route::post('/folder/sub_user_form','FolderController@sub_user_form')->name('folder_sub_user'); // маршрут открывающий виждет подписки
// подписывание папки
Route::post('/folder/vd_find_folder','FolderController@vd_find_folder')->name('vd_find_folder'); // ищет дочерние папки включая корень монтирования
Route::delete('/folder/vd_find_folder/root/{folder}','FolderController@rootmount')->name('rootmount');// указывание корня монтирования
Route::post('/folder/vd_sub_user/root','FolderController@folders_sub')->name('folders_sub');// подписывание папок
//отписка
Route::get('/folder_un_list_group/{folder}','FolderController@un_list_group')->name('folder_un_list_group'); //вывод списка групп
Route::post('/folder/un_sub_user_form','FolderController@un_sub_user_form')->name('un_sub_user_form'); // маршрут открывающий виждет отподписки
Route::post('/folder/un_vd_sub_user','FolderController@folders_un_sub')->name('folders_un_sub');// отписывание папок
// рекурсивное удаление группы
Route::get('/user_group/group_delete/{group}','GroupController@delete')->name('group_destroy'); // удаление виждет
Route::delete('/user_group/group_drop/{group}','GroupController@group_drop')->name('group_drop');// запрос виджета
//увеличение и меньшение
Route::delete('/add_public/{group}','GroupController@add_public')->name('add_public');// запрос увеличения подписок
Route::delete('/sub_public/{group}','GroupController@sub_public')->name('sub_public');// запрос уменьшения подписок
// отписка опубликованных папок
Route::post('/folder/un_vd_find_folder','FolderController@vd_find_folder_un')->name('vd_find_folder_un'); 
