<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class file extends Model
{
    use SoftDeletes;
    
    public function getRouteKeyName() // идентификация по ключу
    {
        return 'slug';
    }
    protected $fillable = [
        'user_id', 'user_name', 'size','server_name','server_path', 'parent', 'slug'
    ];
    public function folder()
    {
        return $this->belongsTo('App\folder', 'parent', 'id');
    }
}
