<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $primarykey = 'id';
    protected $keyType = 'int';
    public $incrementing = true; // public
    public $timestamps = true; //public

    protected $fillable = [
        'name',
        'guard_name',
    ];
    
}
