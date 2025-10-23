<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionImageActivity extends Model
{
    protected $table = 'transaction_image_activity';

    protected $primaryKey = 'id_image_activity';
    public $incrementing = true;
    protected $keyType = 'int';


    protected $fillable = [
        'id_transaction_detail',
        'description',
        'image_path',
    ];


    public function transactionDetail()
    {
        return $this->belongsTo(TransactionDetails::class, 'id_transaction_detail', 'id_transaction_detail');
    }
}
