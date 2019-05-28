<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\folder;
use App\file;
use Illuminate\Support\Str;
use App\group;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $current_user = Auth::user();
        //корневой каталог пользователя
        $root_users = folder::where('user_id', $current_user->id)
                            ->where('root', '1')
                            ->first();
        $children_folder = folder::where('parent', $root_users->id)
                            ->where('user_id', $current_user->id) // дочерние каталоги принадлежащие пользователю
                            ->get();                    
        //dd($children_folder);

        // foldername for title
        $folder_title = $root_users->title; // заголовок
        $parent_folder = $root_users; // родительский каталог
        // упаковка файлов в папке
        $children_file = file::where('parent', $root_users->id)
                                ->where('user_id', $current_user->id) // дочерние файлы принадлежащие пользователю
                                ->get();

        $public_groups = group::where('user_id', $current_user->id)->where('public_folder_count', '>', 0)->get();
                                
        return view('home', compact('children_folder','folder_title','parent_folder','children_file','public_groups'));    
    }
}
