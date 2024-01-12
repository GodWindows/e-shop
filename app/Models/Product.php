<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'price',
        'images',
        'nbr_images',
        'discount_price',
    ];

    public function user()
    {
        return $this->belongsTo(Category::class);
    }
}
