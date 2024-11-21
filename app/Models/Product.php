<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Связь с материалами через таблицу `product_materials`.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
 // Модель Product
    public function materials()
    {
        return $this->belongsToMany(Material::class, 'product_materials')->withPivot('quantity');
    }

    // Модель Warehouse
    public function material()
    {
        return $this->belongsTo(Material::class);
    }

}
