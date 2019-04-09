<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\folder;
use Illuminate\Support\Str;

class MyAvt extends Controller
{
    public function username()
    {
        return 'login';
    }
    public function LDAD_serv($login,$password)
    {
        //код запроса на AD
        return true; // для регистрации нескольких пользователей или false

    }

    public function login(Request $request) // контроллер авторизации
     {
       $login = $request['login'];
       $password = $request['password'];

        if ( Auth::attempt(['login' => $login, 'password' => $password]) ): // есть в локальной БД
            if(Auth::user()->enable === 1) 
                return redirect()->route('home');
            else 
                {
                    Auth::logout();
                    $my = "Отказано в доступе";
                    return view('welcome',compact('my'));
                }
        elseif ($this->LDAD_serv($login,$password)):

            $date = $request->validate([
                'login' => 'required', 'string', 'max:25',
                'password' => 'required', 'string', 'min:5', 'confirmed'
            ]);
           $new_user = User::create([
                'login' => $date['login'],
                'password' => Hash::make($date['password']),
                'path' => "public\\".$date['login'],
                'size' => '250',
                'use_size'=> '0'
            ]);
            $new_root = folder::create([
                'user_name'=> $new_user->path, // пользовательское имя для root каталога не отобразится
                'user_id' => $new_user->id,
                'server_name' => $new_user->path,
                'root' => '1',
                'slug' => (string) Str::uuid(),
                'title' =>$new_user->login
            ]);
            Storage::makeDirectory($new_user->path); // создали папку 
                // авторизируемся в сисстеме
                Auth::attempt(['login' => $login, 'password' => $password]);    
            return redirect()->route('home');
        else:
            $my = "Не верный логин или пароль";
            return view('welcome',compact('my'));
        endif;
     }
     public function logout(Request $request)
     {
         $this->guard()->logout();
         $request->session()->invalidate();
         return redirect('/');
     }
     protected function guard()
     {
         return Auth::guard();
     }
 
}
