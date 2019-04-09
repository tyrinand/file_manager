<?php

namespace App\Http\Controllers;

use App\folder;
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

    public function index()
    {
        //
    }

    public function create(folder $folder)
    {
        return view('folder.create',compact('folder')); 
    }

    public function store(Request $request)
    {
        $parent_folder = folder::where('slug', $request['parent'])->first(); // родитель только 1     

        $date = $request->validate([
            'user_name' => 'required|string|max:25'
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

    public function show(folder $folder)
    {
        //
    }

    public function edit(folder $folder)
    {
      
    }

    public function update(Request $request, folder $folder)
    {
        //
    }

    public function destroy(folder $folder)
    {
        $parent_folder = folder::where('id', $folder->parent)
                        ->where('user_id', Auth::user()->id)
                        ->first();
        if($folder->user_id == Auth::user()->id)  // если пользователь имеет право доступа
        {
            $folder->delete();
            Storage::deleteDirectory($folder->server_name);
        }
        return redirect()->route('folder_child',$parent_folder); // отображение родительской папки пока что home
    }
    public function inter_parent_out_child(folder $folder)
    {
        $parent_folder = $folder; // родительская папка
        $current_user = Auth::user();

        $children_folder = folder::where('parent', $parent_folder->id)
        ->where('user_id', $current_user->id) // дочерние каталоги принадлежащие пользователю
        ->get();                    

        $folder_title =  $parent_folder->title;
        // необходимо соответствие имен
        return view('home', compact('children_folder','folder_title','parent_folder'));    
    }
    public function inter_child_out_parent(folder $folder)
    {

    }
}
