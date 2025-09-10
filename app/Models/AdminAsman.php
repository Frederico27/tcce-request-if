<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminAsman extends Model
{
    protected $table = 'admin_asman';
    protected $primaryKey = 'id_admin_asman';

    protected $keyType = 'int';
    public $incrementing = true; // Use true if the primary key is auto-incrementing
    public $timestamps = false;

    protected $fillable = [
        'id_admin',
        'id_asman',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function asman()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
