<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'image';
    protected $primaryKey = 'id_image';
    public $incrementing = true; // Assuming id_image is auto-incrementing
    protected $keyType = 'int'; // Assuming id_image is an integer
    protected $fillable = ['file_path'];
    public $timestamps = true;
}
