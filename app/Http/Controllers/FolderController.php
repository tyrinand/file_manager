<?php

namespace App\Http\Controllers;

use App\folder;
use App\file;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Gate;
use Illuminate\Support\Facades\DB;
use App\sub_user;
use App\public_folder;
use App\group;


class FolderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(folder $folder)
    {   
        if (Gate::denies('holder', $folder)) { 
            return redirect()->route('logout');
        }
            
        return view('folder.create',compact('folder')); 
    }

    public function store(Request $request)
    {
        $parent_folder = folder::where('slug', $request['parent'])->first(); // родитель только 1

        if (Gate::denies('holder', $parent_folder)) {
            return redirect()->route('logout');
        }
                
            $date = $request->validate([
                'user_name' => 'required|string|max:225'
            ]);

        $date["user_id"] = Auth::user()->id; // ссылка на пользователя
        $date["parent"] = $parent_folder->id; // ссылка на родителя
        $date["slug"] = (string) Str::uuid();
        $date["server_name"] = "*";
        $date["title"] = $parent_folder->title."\\".$date["user_name"];

        $new_folder = folder::create($date);
        
        $date["server_name"] =$parent_folder->server_name."\\".$new_folder->id; // папка получит имя = id
        
        $new_folder->update($date);

        Storage::makeDirectory($new_folder->server_name);

        return redirect()->route('folder_child',$parent_folder); // отображение родительской папки пока что home
    }

    public function edit(folder $folder)
    {
        if (Gate::denies('holder', $folder)) {
            return redirect()->route('logout');
        }
        
        $parent_folder = folder::where('id', $folder->parent)->first();

        return view('folder.edit', compact('folder','parent_folder'));   
    }

    public function update(Request $request, folder $folder)
    {
        if (Gate::denies('holder', $folder)) {
            return redirect()->route('logout');
        }
        $parent_folder = folder::where('id', $folder->parent)->first();

        $date = $request->validate([
            'user_name' => 'required|string|max:225'
        ]);
        $date["title"] = $parent_folder->title."\\".$date["user_name"];
        $folder->update($date);
        return redirect()->route('folder_child',$parent_folder);
    }

    public function destroy(folder $folder)
    {
        if (Gate::denies('holder', $folder)) {
            return redirect()->route('logout');
        }
            $folders =  folder::where('parent', $folder->id)->count();
            $files = file::withTrashed()->where('parent', $folder->id)->count();
            $parent_folder = folder::where('id', $folder->parent)
                        ->where('user_id', Auth::user()->id)
                        ->first();

            if($folder->public_folder === 1)
            {
                return redirect()->route('folder_child',$parent_folder)->with('status', 'Для удаления отпишите папку от группы'); // отображение родительской папки пока что home
            }            
            if( ($folders > 0) || ($files > 0))
                {  
                    return redirect()->route('folder_child',$parent_folder)->with('status', 'Для удаления папки удалите дочерние элементы'); // отображение родительской папки пока что home
                }
            else 
                {
                    $folder->delete();
                    Storage::deleteDirectory($folder->server_name);
                    return redirect()->route('folder_child',$parent_folder);
                }
    }
    public function inter_parent_out_child(folder $folder)
    {
        if (Gate::denies('holder', $folder)) {
            return redirect()->route('logout');
        }
                $parent_folder = $folder; // родительская папка

                $children_folder = folder::where('parent', $parent_folder->id)->get();                    

                $folder_title =  $parent_folder->title;
                // необходимо соответствие имен
                $children_file = file::where('parent',$parent_folder->id)->get();

                return view('home', compact('children_folder','folder_title','parent_folder','children_file'));            
    }
    public function inter_child_out_parent(folder $folder)
    {
        if (Gate::denies('holder', $folder)) {
            return redirect()->route('logout');
        }
                $child = $folder;
                $parent_folder = folder::where('id', $child->parent) 
                    ->where('user_id', Auth::user()->id)
                    ->first();
        return redirect()->route('folder_child',$parent_folder);
    }
    public function root_folder()
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

        $children_file = file::where('parent',$root_users->id)->get();
        // foldername for title
        $folder_title = $root_users->title; // заголовок
        $parent_folder = $root_users; // родительский каталог

        $public_groups = group::where('user_id', $current_user->id)->where('public_folder_count', '>', 0)->get();

        return view('home', compact('children_folder','folder_title','parent_folder','children_file','public_groups'));
    }
    public function serch_str(Request $request)
    {
        $fl_slug = $request['parent_folder'];
        $str_find = $request['str_find'];

        $parent_folder = folder::where('slug', $fl_slug)->first();

        if (Gate::denies('holder', $parent_folder)) {
            return redirect()->route('logout');
        }
        
        $children_folder = DB::table('folders')
                ->where('user_name', 'like', "$str_find%")
                ->where('user_id', $parent_folder->user_id)
                ->get();

        $children_file = DB::table('files')
                ->where('user_name', 'like', "$str_find%")
                ->where('user_id', $parent_folder->user_id)
                ->get(); 
        if($children_folder->isEmpty() && $children_file->isEmpty()) 
            return view('folder.empty',compact('parent_folder'));
            
        $folder_title = "Результаты поиска";
        return view('home', compact('children_folder','folder_title','parent_folder','children_file','str_find')); 
    }
    //публикация папок
    public function list_group(folder $folder)
    {
        if (Gate::denies('holder', $folder)) {
            return redirect()->route('logout');
        }
        $parent_folder = folder::find($folder->parent);

        $list_group = sub_user::where('user_id', Auth::user()->id)->get();

        return view('folder.group_list', compact('parent_folder','folder','list_group')); 
    }
    public function sub_user_form(Request $request)
    {
        $fl_slug = $request['folder'];
        $grop_id = $request['group'];
        
        $grop = group::where('id', $grop_id)->first();

        $folder = folder::where('slug', $fl_slug)->first();

        if (Gate::denies('holder', $folder)) {
            return redirect()->route('logout');
        }
        $parent_folder = folder::find($folder->parent);
        $list_group = sub_user::where('user_id', Auth::user()->id)->get();

        $public_folder = public_folder::where('group_id', '=', $grop_id )->where('folder_id', $folder->id)->get();
        
        if($public_folder->isEmpty()) // была ли папка уже подписана на группу
        {
            return view('folder.sub_user_vd', compact('parent_folder','folder', 'grop'));
        }
        else
        {
            $request->session()->flash('status', 'Папка уже опубликована в этой группе');
            return view('folder.group_list', compact('parent_folder','folder','list_group'));
        }
    }
    public function vd_find_folder(Request $request)
    {
        $fl_slug = $request['folder'];
        $folder = folder::where('slug', $fl_slug)->first();
       // return response($folder, 200);
       
       $public_folders = collect();
       $queue = collect();
       $queue->push($folder); 
       do {// цикл
           $action_node = $queue->pop(); // извленечие последнего элемента
           $public_folders->push($action_node); // текущий узел в результат добавление только тек узла к результату
           $children_folder = folder::where('parent', $action_node->id)->get(); // поиск детей
           $queue = $queue->merge($children_folder); // добавление в очередь на рассмотрение 
       }
        while (!$queue->isEmpty());

         $slug_result = array();

        foreach($public_folders as $pf)
        {
            array_push($slug_result, $pf->slug);
        }
        return response($slug_result, 200);
    }
    public function vd_find_folder_un(Request $request) // контроллер ищет опубликованные папки нужен для отписки, если добавили новую папку
    {
        $fl_slug = $request['folder'];
        $folder = folder::where('slug', $fl_slug)->first();
       // return response($folder, 200);
       
       $public_folders = collect();
       $queue = collect();
       $queue->push($folder); 
       do {// цикл
           $action_node = $queue->pop(); // извленечие последнего элемента
           $public_folders->push($action_node); // текущий узел в результат добавление только тек узла к результату
           $children_folder = folder::where('parent', $action_node->id)->where('public_folder', 1)->get(); // поиск детей
           $queue = $queue->merge($children_folder); // добавление в очередь на рассмотрение 
       }
        while (!$queue->isEmpty());

         $slug_result = array();

        foreach($public_folders as $pf)
        {
            array_push($slug_result, $pf->slug);
        }
        return response($slug_result, 200);
    }
    public function rootmount(folder $folder)
    {
        $folder->root_mount = true;
        $folder->public_folder = true;
        $folder->save();

        return response()->json('Sucsess', 200);    
    }
    public function folders_sub(Request $request)
    {
        $fl_slug = $request['folder'];
        $gr_slug = $request['group'];
        $root_mount_slug = $request['root_mount'];

        $folder = folder::where('slug', $fl_slug)->first();
        $grop = group::where('slug', $gr_slug)->first();
        $root_mount = folder::where('slug', $root_mount_slug)->first();

        $folder->public_folder = true;
        $folder->save(); // папка публичная

        $new_public_folder = public_folder::create([
            'holder_id'=> $grop->user_id, 
            'group_id' => $grop->id,
            'root_mount' => $root_mount->id,
            'sub_user' => Auth::user()->id,
            'folder_id' => $folder->id
        ]);

        return response()->json('Sucsess', 200);
    }
    // отписка
    public function un_list_group(folder $folder)
    {
        if (Gate::denies('holder', $folder)) {
            return redirect()->route('logout');
        }
        $parent_folder = folder::find($folder->parent);

        $list_public_folder = public_folder::where('root_mount', $folder->id)->where('folder_id',$folder->id)->get();

        $list_group = collect();

        foreach($list_public_folder as $ls)
        {
            $gr = group::where('id', $ls->group_id)->first();
            $list_group->push($gr);
        }
        return view('folder.group_un_list', compact('parent_folder','folder','list_group')); 
    }
    public function un_sub_user_form(Request $request)
    {
        $fl_slug = $request['folder'];
        $grop_id = $request['group'];
        
        $grop = group::where('slug', $grop_id)->first();

        $folder = folder::where('slug', $fl_slug)->first();

        if (Gate::denies('holder', $folder)) {
            return redirect()->route('logout');
        }
        $parent_folder = folder::find($folder->parent);
        

        return view('folder.un_sub_user_vd', compact('parent_folder','folder', 'grop'));
    }
    public function folders_un_sub(Request $request)
    {
        
        $fl_slug = $request['folder'];
        $gr_slug = $request['group'];

        $folder = folder::where('slug', $fl_slug)->first();
        $grop = group::where('slug', $gr_slug)->first();

        $folder->public_folder = false;
        $folder->root_mount = false;
        $folder->save(); // папка  не публичная

        $pub_fl = public_folder::where('group_id', $grop->id)->where('folder_id', $folder->id)->first();
        $pub_fl->delete();

        return response()->json('Sucsess', 200);
    }
}             
