<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnImage extends Model
{
    protected $table = 'return_images';
    protected $primaryKey = null; // No primary key
    public $incrementing = false; // Disable auto-incrementing
    protected $keyType = 'string'; // Assuming UUIDs are strings
    protected $fillable = ['transaction_id', 'id_image'];
    public $timestamps = true;
}
