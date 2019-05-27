<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class group extends Model
{
    public function getRouteKeyName() // идентификация по ключу
    {
        return 'slug';
    }
    
    protected $fillable = [
        'title', 'user_id', 'user_login','slug', 'public_folder_count', 'user_sub_count'
    ];

    public function sub_user() // получение всех подписчиков
    {
        return $this->hasMany('App\sub_user','group_id', 'id');
    }
    public function public_folder() // получение всех папок много публичных
    {
        return $this->hasMany('App\public_folder','group_id', 'id');
    }
}
