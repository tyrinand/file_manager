<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class public_folder extends Model
{
    protected $fillable = [
        'holder_id', 'group_id','root_mount', 'sub_user','folder_id'
    ];
    public function group() // получение группы
    {
        return $this->belongsTo('App\group','group_id', 'id');
    }
    public function myfolder() // получение папки
    {
        return $this->belongsTo('App\folder','folder_id', 'id');
    }
}
