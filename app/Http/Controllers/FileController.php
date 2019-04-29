<?php

namespace App\Http\Controllers;
use App\folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Str;
use App\file;

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
        return view('file.upload',compact('folder')); 
    }
    public function no_space($error, $parent_folder) // страница для ошибок
    {  
        return view('file.no_space',compact('parent_folder','error')); 
    }
    public function upload(Request $request) //  сохранение $request->file('image')->store('test','public');
    {  
        $file = $request->file('image'); // сам файл в запросе

        $name = $request->file('image')->getClientOriginalName();
        $t_name = $this->translit($name); // перевод в транслит имени

        $id_folder = $request['folder'];
        $parent_folder = folder::find($id_folder);// получение родительского каталога

        $file_size =  $file->getSize(); // размер в байтах
        $free_size = Auth::user()->size - Auth::user()->use_size - $file_size; // свободное место = общее - испол. - тек. размер

         if( $free_size > 0 )
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
                'server_path' => $parent_folder->server_name."\\".$t_name,
                'parent' =>  $id_folder
            ]);

            $path = Storage::putFileAs($parent_folder->server_name, $file, $t_name); // сохранение файла в родительскую папку с именем
            //return response( $file_size, 200);
            return response()->json('Sucsess', 200);
        }
       else 
        {
            return response()->json('no_space', 200);
        }
             
    }
}
