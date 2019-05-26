<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sub_user extends Model
{
    protected $fillable = [
        'user_id', 'group_id'
    ];
    public function group() // получение группы
    {
        return $this->belongsTo('App\group','group_id', 'id');
    }
}
