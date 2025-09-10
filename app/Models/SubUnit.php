<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubUnit extends Model
{
    protected $table = 'sub_unit';
    protected $primaryKey = 'id_sub_unit';
    protected $keyType = 'int';
    public $incrementing = true; // public
    public $timestamps = false; //public


    protected $fillable = [
        'nama_sub_unit',
        'id_sub_unit',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'id_sub_unit', 'id_sub_unit');
    }
}
