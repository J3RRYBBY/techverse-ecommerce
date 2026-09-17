<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'image', 'image_public_id'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
