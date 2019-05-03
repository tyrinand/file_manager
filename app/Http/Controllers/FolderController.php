<?php

namespace App\Http\Controllers;

use App\folder;
use App\file;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FolderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function blong_user(folder $folder) // метод принадлежности
    {
        if(Auth::user()->id === $folder->user_id):
            return true;
        else:
            return false;
        endif;
    }
    public function create(folder $folder)
    {   
        if($this->blong_user($folder)):
            return view('folder.create',compact('folder')); 
        else:   
            return redirect()->route('logout');
        endif;   
    }

    public function store(Request $request)
    {
        $parent_folder = folder::where('slug', $request['parent'])->first(); // родитель только 1

        if($this->blong_user($parent_folder)):
                
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

        else:   return redirect()->route('logout');
        endif;
    }

    public function edit(folder $folder)
    {
        if($this->blong_user($folder)):
                $parent_folder = folder::where('id', $folder->parent)->first();
                return view('folder.edit', compact('folder','parent_folder'));  
        else:  
                 return redirect()->route('logout');
        endif;
        
    }

    public function update(Request $request, folder $folder)
    {
        if($this->blong_user($folder)):
                $parent_folder = folder::where('id', $folder->parent)->first();

                $date = $request->validate([
                    'user_name' => 'required|string|max:225'
                ]);
                $date["title"] = $parent_folder->title."\\".$date["user_name"];
                $folder->update($date);
                return redirect()->route('folder_child',$parent_folder);
        else:   
            return redirect()->route('logout');
        endif;
        
    }

    public function destroy(folder $folder)
    {
        if($this->blong_user($folder)):

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
        else:   return redirect()->route('logout');
        endif;
    }
    public function inter_parent_out_child(folder $folder)
    {
        if($this->blong_user($folder)):
                $parent_folder = $folder; // родительская папка

                $children_folder = folder::where('parent', $parent_folder->id)->get();                    

                $folder_title =  $parent_folder->title;
                // необходимо соответствие имен
                $children_file = file::where('parent',$parent_folder->id)->get();

                return view('home', compact('children_folder','folder_title','parent_folder','children_file')); 
        else:   return redirect()->route('logout');
        endif;    
           
    }
    public function inter_child_out_parent(folder $folder)
    {
        if($this->blong_user($folder)):
                $child = $folder;
                $parent_folder = folder::where('id', $child->parent) 
                    ->where('user_id', Auth::user()->id)
                    ->first();
                return redirect()->route('folder_child',$parent_folder);
        else:   return redirect()->route('logout');
        endif;
       
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
}
