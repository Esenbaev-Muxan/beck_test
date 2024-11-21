<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\Product;
use App\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    public function run()
    {
        // Create products
        $product1 = Product::create(['name' => 'Product 1', 'code' => 'P001']);
        $product2 = Product::create(['name' => 'Product 2', 'code' => 'P002']);
    
        // Create materials
        $mato = Material::create(['name' => 'Mato']);
        $ip = Material::create(['name' => 'Ip']);
        $tugma = Material::create(['name' => 'Tugma']);
        $zamok = Material::create(['name' => 'Zamok']);
    
        // Create warehouse entries
        Warehouse::create(['material_id' => $mato->id, 'remainder' => 12, 'price' => 1500]);
        Warehouse::create(['material_id' => $mato->id, 'remainder' => 200, 'price' => 1600]);
        Warehouse::create(['material_id' => $ip->id, 'remainder' => 40, 'price' => 500]);
        Warehouse::create(['material_id' => $ip->id, 'remainder' => 300, 'price' => 550]);
        Warehouse::create(['material_id' => $tugma->id, 'remainder' => 500, 'price' => 300]);
        Warehouse::create(['material_id' => $zamok->id, 'remainder' => 1000, 'price' => 2000]);
    
        // Associate materials with products (using pivot table)
        $product1->materials()->attach([
            $mato->id => ['quantity' => 0.8],
            $ip->id => ['quantity' => 5],
            $tugma->id => ['quantity' => 10]
        ]);
    
        $product2->materials()->attach([
            $mato->id => ['quantity' => 1.4],
            $ip->id => ['quantity' => 15],
            $zamok->id => ['quantity' => 1]
        ]);
    }
    
}
