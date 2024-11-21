<?php 

namespace App\Repositories;

use App\Models\Product;
use App\Models\Warehouse;

class ProductRepository
{
    public function getProductWithMaterials($productId)
    {
        return Product::with('materials')->find($productId);
    }

    public function getWarehousesForMaterials($materialIds)
    {
        return Warehouse::whereIn('material_id', $materialIds)->get();
    }
}
