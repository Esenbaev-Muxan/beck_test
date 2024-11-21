<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMaterial extends Model
{
    use HasFactory;

    /**
     * Поля, которые можно массово заполнять.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'material_id',
        'quantity',
    ];

    /**
     * Отключить временные метки, если таблица `product_materials` не содержит их.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Определение связи с моделью продукта.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Определение связи с моделью материала.
     */
    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
