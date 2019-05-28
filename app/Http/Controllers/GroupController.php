<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\group;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Gate;
use App\sub_user;
use App\public_folder;
use App\folder;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $created_groups = group::where('user_id', Auth::user()->id)->get();
       
        $inside_group = sub_user::where('user_id', Auth::user()->id)->get();//получить список групп
        
        $public_user_folder = folder::where('user_id', Auth::user()->id)->where('root_mount', 1)->get();

        $public_folder = collect();

        foreach($public_user_folder  as $pl)
        {
            $folders = public_folder::where('folder_id', $pl->id)->first();
            $public_folder->push($folders);
        }
        
        return view('group.panel', compact('created_groups','public_folder','inside_group')); 
    }
    public function create()
    {
        return view('group.create');
    }
    public function store(Request $request)
    {  
            $date = $request->validate([
                'title' => 'required|string|max:225'
            ]);

        $date["user_id"] = Auth::user()->id; // ссылка на пользователя
        $date["user_login"] = Auth::user()->login; // ссылка на родителя
        $date["slug"] = (string) Str::uuid();
        $date["public_folder_count"] = 0;
        $date["user_sub_count"] = 0;

        $new_group = group::create($date);

        return redirect()->route('user_group'); 
    }
    public function share(group $group) // форма для отправки
    {   
        if (Gate::denies('holder', $group)) { 
            return redirect()->route('logout');
        }

        return view('group.share',compact('group')); 
    }
    public function delete(group $group) // форма для удаления группы
    {   
        if (Gate::denies('holder', $group)) { 
            return redirect()->route('logout');
        }

        return view('group.vd_delete',compact('group')); 
    }
    public function group_drop(group $group) // форма для удаления группы
    {
        $pb_fl = public_folder::where('group_id', $group->id)->get();

        if($pb_fl->isNotEmpty())
            {
                foreach($pb_fl as $fl)
                {
                    $remp_fl = folder::where('id', $fl->folder_id)->first();
                    $remp_fl->public_folder = false;
                    $remp_fl->root_mount = false;
                    $remp_fl->save();

                    $fl->delete(); //удаление всех публичных папок
                }
            }
        $sub_users = sub_user::where('group_id',$group->id)->get();
        if($sub_users->isNotEmpty())
            {
                foreach($sub_users as $su)
                {
                    $su->delete(); //удаление всех подписчиков
                }
            }
            $group->delete();

        return response()->json('Sucsess', 200);
    }
    public function add_public(group $group) // увеличение числа публичных папок
    {
        $group->public_folder_count++;
        $group->save();
    }
    public function sub_public(group $group) // уменьшение числа публичных папок
    {
        $group->public_folder_count--;
        $group->save();
    }
}
