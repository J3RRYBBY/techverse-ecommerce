<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['product_variant_id', 'image', 'image_public_id'];

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
