<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionImageDishRebus extends Model
{
    protected $table = 'transaction_dish_rebush';

    protected $primaryKey = 'id_image_proof_dish_rebush';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_transaction_detail',
        'description',
        'image_path',
    ];
}
