<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\file;
use Illuminate\Support\Facades\Auth;
use App\group;
use App\sub_user;

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
    public function form_sub(group $group) // форма для загрузки
    {  
        return view('group.download_form',compact('group')); 
    }
    public function group_login_sub(Request $request) // форма для загрузки
    {
        $login = $request['login'];
        $password = $request['password'];
        $slug = $request['group'];
        $group = group::where('slug', '=', $slug )->first();

        if ( Auth::attempt(['login' => $login, 'password' => $password]) ): // есть в локальной БД
            
            if($group->user_id === Auth::user()->id) // владелец группы
                {
                    $str_hello = "Вы владелец группы !!!";
                    return redirect()->route('home')->with('status',$str_hello);
                } 
            $subs_users = sub_user::where('group_id', '=', $group->id )->where('user_id', Auth::user()->id)->get();

            if( $subs_users->isNotEmpty() ) // владелец группы
            {
                $str_hello = "Вы уже подписаны";
                return redirect()->route('home')->with('status',$str_hello);
            }

            $group->user_sub_count++;
            $group->save(); // добавился подписчик
            //обновление таблицы подписки
            sub_user::create([
                'user_id' => Auth::user()->id,
                'group_id' => $group->id
            ]);

            $str_hello = "Вы вступили в группу $group->title";
            return redirect()->route('home')->with('status',$str_hello);

        elseif ($this->LDAD_serv($login,$password)):

            $my = "Вас нет в БД 'облака', пожалуйста войдите в систему"; 
            return view('group.download_form',compact('group','my'));   //есть в AD, но нет в БД

        else:
            $my = "Не верный логин или пароль"; 
            return view('group.download_form',compact('group','my'));   // нигде нет вернуться назад
        endif;
    }
}
