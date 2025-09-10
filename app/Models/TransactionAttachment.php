<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionAttachment extends Model
{
    protected $table = 'transactions_attachment';
    protected $primaryKey = 'id_transaction_attach'; // No primary key
    public $incrementing = true; // Disable auto-incrementing
    protected $keyType = 'int'; // Assuming UUIDs are strings
    public $timestamps = true;

    protected $fillable = [
        'id_transaction_detail',
        'file_path',
        'file_type',
        'uploaded_by',
    ];
}
