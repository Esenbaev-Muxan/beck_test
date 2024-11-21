<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_materials')
                    ->withPivot('quantity');
    }

    public function warehouses()
    {
        return $this->hasMany(Warehouse::class);
    }
}
