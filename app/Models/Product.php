<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $visible = ['id', 'name', 'description', 'price', 'created_at', 'updated_at'];

    /**
     * Get the JSON structure for a product.
     *
     * @return array
     */
    public static function getJsonStructure(): array
    {
        return [
            'id',
            'name',
            'description',
            'price',
            'created_at',
            'updated_at',
        ];
    }
}
