<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopItem extends Model
{
    protected $fillable = [
        'slug',
        'category',
        'name',
        'description',
        'price',
        'data',
        'preview',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'data'  => 'array',
            'price' => 'integer',
        ];
    }
}
