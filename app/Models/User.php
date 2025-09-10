<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasRoles;
    use SoftDeletes;

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true; // public
    public $timestamps = true; //public

    protected $fillable = [
        'nik',
        'full_name',
        'position_name',
        'phone_number',
        'id_sub_unit',
    ];

    public function subUnit()
    {
        return $this->belongsTo(SubUnit::class, 'id_sub_unit');
    }

    //relationship admin-asman

    // Sebagai admin: relasi satu admin ke banyak baris admin_asman
    public function adminAsman()
    {
        return $this->hasMany(AdminAsman::class, 'id_admin');
    }

    // Sebagai asman: satu baris di admin_asman
    public function asmanOf()
    {
        return $this->hasOne(AdminAsman::class, 'id_asman');
    }

    public function transactionsFrom()
    {
        return $this->hasMany(Transactions::class, 'from_user_id');
    }



}
