<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function categoryProduct()
    {
        return $this->hasMany(ProductCategories::class, 'id', 'category_id');
    }
}
