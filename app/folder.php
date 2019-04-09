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
        'user_name', 'user_id', 'server_name','root','parent', 'slug'
    ];

    public function user() // получение пользователя
    {
        return $this->belongsTo('App\User','user_id', 'id');
    }
}
