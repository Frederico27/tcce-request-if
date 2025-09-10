<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    
    protected $table = 'transactions';
    protected $primaryKey = 'id_transactions';
    protected $keyType = 'string';
    public $incrementing = 'false'; // public
    public $timestamps = true; //public

    protected $fillable = [
        'id_transactions',
        'action',
        'type',
        'description',
        'amount',
        'remaining_amount',
        'status',
        'from_user_id',
        'to_user_id',
        'requested_by',
        'approved_by',
        'parent_transaction_id'
    ];

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

}
