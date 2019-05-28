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
use Illuminate\Support\Facades\DB;
use App\group;
use App\public_folder;

class AdminControl extends Controller
{
    public function index()
    {
        if (Gate::denies('admin')) { 
            return redirect()->route('logout');
        }

        $users = User::where('login', '<>', 'SuperUser')->paginate(25);
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
    
        $user_login = $User->login;
        $user_size = $User->use_size;

        $files = file::withTrashed()->where('user_id', $User->id )->get(['slug','size']);
        $count_file = $files->count();

        $folders = folder::where('user_id', $User->id )->where('root', '0')->get(['slug']);
        $count_folder = $folders->count();
        
        $groups = group::where('user_id', $User->id )->get(['slug']);
        $count_groups = $groups->count();

        return view('admin.delete_all', compact('files','folders','count_folder','count_file','user_login','user_size','groups','count_groups')); 
    }
    public function admin_delete_file($slug) 
    {
        if (Gate::denies('admin')) { 
            return ['result' => false ]; // аякс запрос редирект не возможен
        }

        $file = file::withTrashed()->where('slug', $slug)->first();

        $user = User::where('id', $file->user_id )->first();

        $user->use_size -= $file->size;
        $user->save(); 
        Storage::delete($file->server_path);
        $file->forceDelete();

        return ['result' => true];
    }
    public function admin_block_user($login) 
    {
        if (Gate::denies('admin')) { 
            return response(401); // аякс запрос редирект не возможен
        }

        $User = User::where('login', $login )->first();

        $User->enable = 0;
        $User->save();
        return response(200);
    }
    public function admin_delete_folder(folder $folder) 
    {
        if (Gate::denies('admin')) { 
            return response(401); // аякс запрос редирект не возможен
        }
        if($folder->public_folder === 1)
        {
           $pf = public_folder::where('folder_id', $folder->id)->first();
           $pf->delete();
        }

        Storage::deleteDirectory($folder->server_name);
        $folder->delete();

        return response(200);
    }        
    public function serch_login(Request $request) // написание контроллера
    {
       if (Gate::denies('admin')) { 
            return redirect()->route('logout');
        }
        $str_find_login = $request['str_find_login'];

        //$users_all = User::where('login', '<>', 'SuperUser')->paginate(25);

        $users = DB::table('users')
            ->where('login', 'like', "$str_find_login%")
            ->paginate(25);

        if($users->isEmpty())
        {
            return view('admin.empty');
        }
        else
        {
            $count_user = $users->count();
            $use_size_total = $users->sum('use_size');
            return view('admin.panel', compact('users','count_user','use_size_total')); 
        }
    }
}
