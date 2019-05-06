<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\folder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\file;
use Gate;

class AdminControl extends Controller
{
    public function index()
    {
        if (Gate::denies('admin')) { 
            return redirect()->route('logout');
        }

        $users = User::where('login', '<>', 'SuperUser')->paginate(3);
        $count_user = $users->count();
        $use_size_total = $users->sum('use_size');
        return view('admin.panel', compact('users','count_user','use_size_total')); 
    }
}
