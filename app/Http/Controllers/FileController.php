<?php

namespace App\Http\Controllers;
use App\folder;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function upload(folder $folder) // форма для загрузки
    {  
        return view('file.upload',compact('folder')); 
    }
}
