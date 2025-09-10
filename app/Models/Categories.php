<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id_category';
    public $timestamps = false;

    protected $fillable = [
        'category_name',
    ];

    public function subCategories()
    {
        return $this->hasMany(SubCategories::class, 'id_category', 'id_category');
    }
}
