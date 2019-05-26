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
        return view('home', compact('children_folder','folder_title','parent_folder','children_file')); 
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
    public function public_folder(folder $folder)
    {   
        /*$public_files = collect();
        $public_folders = collect(); //  array_push($array, $x);

        $public_folders->push($folder); //  корень
        
        $child_file = file::where('parent',$folder->id)->get(); // 1 уровень меньше
        $children_folder = folder::where('parent', $folder->id)->get();

        $public_files = $public_files->merge($child_file);
        $public_folders = $public_folders->merge($children_folder);
        
        dd($public_files,$public_folders);
        */
        $public_files = collect();
        $public_folders = collect();

        $queue = collect();
        $queue->push($folder); 
        do {// цикл
            $action_node = $queue->pop(); // извленечие последнего элемента

            $public_folders->push($action_node); // текущий узел в результат добавление только тек узла к результату
            $children_folder = folder::where('parent', $action_node->id)->get(); // поиск детей
            $queue = $queue->merge($children_folder); // добавление в очередь на рассмотрение 

            $child_file = file::where('parent',$action_node->id)->get(); // получение родительскх файлов
            $public_files = $public_files->merge($child_file);
        }
            while (!$queue->isEmpty());

        dd($public_folders,$public_files);
    }
}
