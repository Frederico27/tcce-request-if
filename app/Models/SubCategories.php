<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategories extends Model
{
    protected $table = 'sub_categories';
    protected $primaryKey = 'id_sub_category';
    public $timestamps = true;

    protected $fillable = [
        'sub_category_name',
        'id_category',
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class, 'id_category', 'id_category');
    }
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetails::class, 'id_sub_category', 'id_sub_category');
    }
}
