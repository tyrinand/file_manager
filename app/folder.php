<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class folder extends Model
{
    public function getRouteKeyName() // идентификация по ключу
    {
        return 'slug';
    }
    protected $fillable = [
        'user_name', 'user_id', 'server_name','root','parent', 'slug', 'title', 'root_mount','public_folder'
    ];

    public function user() // получение пользователя
    {
        return $this->belongsTo('App\User','user_id', 'id');
    }
    public function files() // много файлов
    {
        return $this->hasMany('App\file','parent', 'id');
    }
    public function public_folders() 
    {
        return $this->hasMany('App\public_folder','folder_id', 'id');
    }
}
