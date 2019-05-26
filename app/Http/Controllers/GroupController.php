<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\group;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Gate;
use App\sub_user;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $created_groups = group::where('user_id', Auth::user()->id)->get();
        $public_folder = collect();
        $inside_group = sub_user::where('user_id', Auth::user()->id)->get();//получить список групп
        
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
}
