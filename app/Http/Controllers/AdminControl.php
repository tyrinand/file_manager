<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\folder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\file;
use Gate;
use Illuminate\Support\Facades\Hash;

class AdminControl extends Controller
{
    public function index()
    {
        if (Gate::denies('admin')) { 
            return redirect()->route('logout');
        }

        $users = User::where('login', '<>', 'SuperUser')->paginate(3);
        $count_user = $users->count();
        $use_size_total = $users->sum('use_size');
        return view('admin.panel', compact('users','count_user','use_size_total')); 
    }
    public function password_change()
    {
        if (Gate::denies('admin')) { 
            return redirect()->route('logout');
        }
        return view('admin.change_pass'); 
    }
    public function update_pass(Request $request)
    {
        if (Gate::denies('admin')) { 
            return redirect()->route('logout');
        }
        $last_pass = $request['last_password'];
        $new_pass = $request['new_password'];
        $new_pass_r = $request['new_password_r'];

         if( Hash::check($last_pass, Auth::user()->password) )
         {
            if( $new_pass === $new_pass_r)
            {
                Auth::user()->password =  Hash::make($new_pass);
                Auth::user()->save();
                return redirect()->route('admin_panel')->with('status', 'Пароль изменен');
            }
            else
            {
                $my = "Новые пароли не совпадают";
                return view('admin.change_pass',compact('my'));
            }
         }
         else
         {
            $my = "Не верный старый пароль";
            return view('admin.change_pass',compact('my'));
         }
    }
    public function block_user(User $User)
    {
        if (Gate::denies('admin')) { 
            return redirect()->route('logout');
        }

        if($User->enable == 1)
        {
            $User->enable = 0;
            $msg = "Пользователю ".$User->login." закрыт доступ";
        }
        else
        {
            $User->enable = 1;
            $msg = "Пользователю ".$User->login." открыт доступ";
        }

        $User->save();
        return redirect()->route('admin_panel')->with('status', $msg);
    }
    public function user_size_form(User $User) // изменение размера
    {
       if (Gate::denies('admin')) { 
            return redirect()->route('logout');
        }
        $size = $User->size;
        $max_size = 1000;
        $min_size = ceil( ( $User->use_size/1048576 ) ) + 1;
        return view('admin.change_size', compact('size','max_size','min_size','User')); 
    }
    public function size_update(Request $request) // изменение размера
    {
       if (Gate::denies('admin')) { 
            return redirect()->route('logout');
        }
        $user_id = $request['user'];
        $size = $request['size_user'];
        $user = User::where('id', '=', $user_id)->first();
        $user->size = $size;
        $user->save();

        return redirect()->route('admin_panel');       
    }
    public function admin_delete_all(User $User) 
    {
       if (Gate::denies('admin')) { 
            return redirect()->route('logout');
        }
    
        $files = file::where('user_id', $User->id )->get(['slug']);
        $count_file = $files->count();

        $folders = folder::where('user_id', $User->id )->where('root', '0')->get(['slug']);
        $count_folder = $folders->count();
        
        return view('admin.delete_all', compact('files','folders','count_folder','count_file')); 
    }
}
