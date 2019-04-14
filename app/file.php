<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class file extends Model
{
    public function getRouteKeyName() // идентификация по ключу
    {
        return 'slug';
    }
    protected $fillable = [
        'user_id', 'user_name', 'size','server_name','server_path', 'parent', 'slug'
    ];
}
