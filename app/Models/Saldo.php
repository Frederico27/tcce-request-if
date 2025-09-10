<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Saldo extends Model
{
    protected $table = 'saldo';
    protected $primaryKey = 'id_user';
    public $timestamps = true;

    protected $fillable = [
        'id_user',
        'balance',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
