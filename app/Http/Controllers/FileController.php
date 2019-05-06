<?php

namespace App\Http\Controllers;
use App\folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Str;
use App\file;
use Gate;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function translit($s)
    {
        $s = (string) $s; // преобразуем в строковое значение
        $s = strip_tags($s); // убираем HTML-теги
        $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
        $s = trim($s); // убираем пробелы в начале и конце строки
        $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
        $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
        $s = preg_replace("/[^0-9a-z-_ .]/ui", "", $s); // очищаем строку от недопустимых символов
        $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
        $s = str_replace(" ", "_", $s); // заменяем пробелы знаком минус
        return $s; // возвращаем результат
    }

    public function uploadform(folder $folder) // форма для загрузки
    {  
        if (Gate::denies('holder', $folder)) { 
            return redirect()->route('logout');
        }

        return view('file.upload1',compact('folder')); 
    }
    public function share(file $file) // форма для отправки
    {   
        if (Gate::denies('holder', $file)) { 
            return redirect()->route('logout');
        }

        $file->public_url = 1; // файл стал публичным
        $file->save();// сохранение

        $parent_folder = folder::find($file->parent);
        return view('file.share',compact('file','parent_folder')); 
    }
    public function close(file $file) // закрытие файла
    {   
        if (Gate::denies('holder', $file)) { 
            return redirect()->route('logout');
        }

        $file->public_url = 0; // файл стал публичным
        $file->save();// сохранение

        $parent_folder = folder::find($file->parent);
        return redirect()->route('folder_child',$parent_folder)->with('status', 'Доступ к файлу закрыт');
    }

    public function download(file $file)  //маршрут загрузки
    {  
        if (Gate::denies('holder', $file)) { 
            return redirect()->route('logout');
        }
        return response()->download(storage_path('app/' . $file->server_path)); //внутренняя загрузка
    }

    public function master_download(file $file)  //маршрут загрузки для владельца
    {   
        if (Gate::denies('holder', $file)) { 
            return redirect()->route('logout');
        }

        return response()->download(storage_path('app/' . $file->server_path)); //внутренняя загрузка
    }

    public function upload(Request $request) //  сохранение $request->file('image')->store('test','public');
    {  
        $file = $request->file('image'); // сам файл в запросе

        $name = $request->file('image')->getClientOriginalName();
        $t_name = $this->translit($name); // перевод в транслит имени

        $id_folder = $request['folder'];
        $parent_folder = folder::find($id_folder);// получение родительского каталога

        $file_size =  $file->getSize(); // размер в байтах
        $free_size = (Auth::user()->size*1048576) - Auth::user()->use_size - $file_size; // свободное место = общее - испол. - тек. размер

        $serv_path = $parent_folder->server_name."\\".$t_name;
         if( $free_size > 0 )
        {  
            if (file::withTrashed()->where('server_path', '=', $serv_path)->count() === 0) //не существует такой файл
            {
                $model = User::where('id', '=', Auth::user()->id)->first();
                $model->use_size += $file_size; // добавление файла
                $model->save();// сохранение новых данных о пользователе

            $new_file = file::create([
                'user_id'=> Auth::user()->id, 
                'slug' => (string) Str::uuid(),
                'user_name' => $name,
                'size' => $file_size,
                'server_name' => $t_name,
                'server_path' => $serv_path,
                'parent' =>  $id_folder
            ]);

            $path = Storage::putFileAs($parent_folder->server_name, $file, $t_name); // сохранение файла в родительскую папку с именем   
            return response()->json('Sucsess', 200);
            }
            else
            {
                return response()->json('Файл '.$name.' уже существует', 201);
            }
            
        }
       else 
        {
            return response()->json('Переполнение хранилища', 200);
        }
             
    }
    // работа с корзиной и удаление
    public function delete_basket(file $file)  //маршрут удаления в корзину
    {  
        if (Gate::denies('holder', $file)) { 
            return redirect()->route('logout');
        }
        $file->public_url = 0; // файл стал непубличным
        $file->save();// сохранение
        $file->delete();
        $parent_folder = folder::find($file->parent);
        return redirect()->route('folder_child',$parent_folder)->with('status', 'Файл перемещен в корзину');
    }
    public function basket_all()  //маршрут удаления в корзину
    {  
        $children_file = file::onlyTrashed()->where('user_id',Auth::user()->id)->get();
        // только удаленные и текущего пользователя
        $total_size = 0;
        foreach ($children_file as $file)
            $total_size +=  $file->size;

        return view('file.trash',compact('children_file', 'total_size')); 
    }
    public function rest($slug)  //восстановление файла
    {  
        $file = file::onlyTrashed()->where('slug',$slug)->first();

        if (Gate::denies('holder', $file)) { 
            return redirect()->route('logout');
        }

        $file->restore();
        return redirect()->route('basket'); 
    }
    public function clear(Request $request)  //удаление
    {  
        $slug = $request['slug'];
        $file = file::onlyTrashed()->where('slug',$slug)->first();

        if (Gate::denies('holder', $file)) { 
            return redirect()->route('logout');
        }

        $user = Auth::user();

        $user->use_size -= $file->size;
        $user->save(); // уменьшили размер
        
        Storage::delete($file->server_path);//удаление файла
        $file->forceDelete(); //удаление объекта

        return redirect()->route('basket');  
    }
    public function clear_basket(Request $request)  //удаление
    {  
        $children_file = file::onlyTrashed()->where('user_id',Auth::user()->id)->get();
        // только удаленные и текущего пользователя
        $user = Auth::user();
        foreach ($children_file as $file)
            {
                $user->use_size -= $file->size;
                $user->save(); // уменьшили размер
                Storage::delete($file->server_path);//удаление файла
                $file->forceDelete(); //удаление объекта
            }
        return redirect()->route('basket')->with('status', 'Корзина очищена');
    }

    public function all_input_basket(Request $request)  //удаление
    {  
        $slug = $request['slug'];
        
        $parent_folder = folder::where('slug',$slug)->first();

        if (Gate::denies('holder', $parent_folder)) { 
            return redirect()->route('logout');
        }

        $children_file = file::where('parent', $parent_folder->id)
                                ->where('user_id', Auth::user()->id) // дочерние файлы принадлежащие пользователю
                                ->get();
        // только удаленные и текущего пользователя
        foreach ($children_file as $file)
            {
                $file->public_url = 0; // файл стал непубличным
                $file->save();// сохранение
                $file->delete();
            }
        return redirect()->route('folder_child',$parent_folder)->with('status', 'Файлы перемещены в корзину');
    }
}
