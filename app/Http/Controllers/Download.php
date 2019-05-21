<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\file;
use Illuminate\Support\Facades\Auth;

class Download extends Controller
{
    public function LDAD_serv($login,$password) // создание пользователя только при входе в систему
    {
        $ldaphost = "10.0.7.10";
	    $ldapport = "389";
	    $domain = "@campus.iate.obninsk.ru";

	    $login = $login.$domain;

	    if (!function_exists("ldap_connect")) {
            return false;
	    }
	    $ad = ldap_connect($ldaphost,$ldapport);

	    if (!$ad){
		    return false;
	    }
	    ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);
	    $res=@ldap_bind($ad, $login, $password);

	    if ($res) {
            return true;
	    }
	    else
		{
			    return false;
		}

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
        if($file->public_url === 0)
            {
                $my = "Файл изъят из доступа";
                return view('file.download_form',compact('file','my')); 
            }
            
        // форма отрисовалась, но файл закрыли

        if ( Auth::once(['login' => $login, 'password' => $password]) ): // есть в локальной БД
            
            return response()->download(storage_path('app/' . $file->server_path));

        elseif ($this->LDAD_serv($login,$password)):

            return response()->download(storage_path('app/' . $file->server_path));

        else:
            $my = "Не верный логин или пароль"; 
            return view('file.download_form',compact('file','my'));  // нигде нет вернуться назад
        endif;
    }
}
