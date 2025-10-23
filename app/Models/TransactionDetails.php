<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetails extends Model
{
    protected $table = 'transactions_details';
    protected $primaryKey = 'id_transaction_detail';
    public $incrementing = true; // Assuming id_transaction_detail is auto-incrementing
    protected $keyType = 'int'; // Assuming id_transaction_detail is an integer
    protected $fillable = [
        'id_transactions',
        'used_for',
        'amount',
        'id_sub_category',
    ];
    public $timestamps = true;

    public function transaction()
    {
        return $this->belongsTo(Transactions::class, 'id_transactions', 'id_transactions');
    }

    public function transactionAttachments()
    {
        return $this->hasMany(TransactionAttachment::class, 'id_transaction_detail', 'id_transaction_detail');
    }

    public function transactionImageActivities()
    {
        return $this->hasMany(TransactionImageActivity::class, 'id_transaction_detail', 'id_transaction_detail');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategories::class, 'id_sub_category', 'id_sub_category');
    }
}
