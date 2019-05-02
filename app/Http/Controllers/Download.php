<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\file;
use Illuminate\Support\Facades\Auth;

class Download extends Controller
{
    public function LDAD_serv($login,$password) // создание пользователя только при входе в систему
    {
        //код запроса на AD
        return false; // для регистрации нескольких пользователей или false
    }

    public function form(file $file) // форма для загрузки
    {  
        return view('file.download_form',compact('file')); 
    }
    public function form_login(Request $request) // форма для загрузки
    {  
        $login = $request['login'];
        $password = $request['password'];
        $file_slug = $request['file'];
        $file = file::where('slug', '=', $file_slug )->first();
// 3 варианта 1 - есть в локальной БД 2 - есть на AC сервере 3 - нет нигде

        if ( Auth::once(['login' => $login, 'password' => $password]) ): // есть в локальной БД
            
            return response()->download(storage_path('app/' . $file->server_path));

        elseif ($this->LDAD_serv($login,$password)):

            return response()->download(storage_path('app/' . $file->server_path));

        else:
            return view('file.download_form',compact('file'));  // нигде нет вернуться назад
        endif;
    }
}
