<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id_transactions';
    protected $keyType = 'string';
    public $incrementing = false; // public
    public $timestamps = true; //public

    protected $fillable = [
        'id_transactions',
        'action',
        'type',
        'activity',
        'description',
        'amount',
        'additional_amount',
        'additional_amount_reason',
        'remaining_amount',
        'status',
        'from_user_id',
        'to_user_id',
        'requested_by',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
        'parent_transaction_id'
    ];

    protected $casts = [
        'approved_by' => 'array',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }
}
