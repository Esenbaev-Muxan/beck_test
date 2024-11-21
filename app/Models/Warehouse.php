<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    /**
     * Поля, которые можно массово заполнять.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'material_id',
        'remainder',
        'price',
    ];

    /**
     * Связь с моделью материала.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
