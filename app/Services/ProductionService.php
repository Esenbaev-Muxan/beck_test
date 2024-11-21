<?php 

namespace App\Services;

use App\Models\Product;
use App\Models\Warehouse;

class ProductionService
{
    // Method to get materials for the given products
    public function getMaterialsForProducts(array $products)
    {
        $response = ['result' => []];
        $allocatedMaterials = []; // To track the allocated quantities

        // Fetch all warehouses for relevant materials in a batch query
        $warehouses = $this->getWarehouses($products);

        foreach ($products as $productRequest) {
            $product = $this->getProductWithMaterials($productRequest['product_id']);
            if (!$product) {
                $response['result'][] = $this->handleProductNotFound($productRequest['product_id']);
                continue;
            }

            $productData = $this->prepareProductData($product, $productRequest['quantity']);

            foreach ($product->materials as $material) {
                $requiredQuantity = $this->calculateRequiredQuantity($productRequest['quantity'], $material);

                $productData['product_materials'] = array_merge(
                    $productData['product_materials'],
                    $this->allocateMaterial($material, $requiredQuantity, $warehouses, $allocatedMaterials)
                );
            }

            $response['result'][] = $productData;
        }

        return $response;
    }

    // Fetch product and its materials
    private function getProductWithMaterials($productId)
    {
        return Product::with('materials')->find($productId);
    }

    // Prepare initial data for the product
    private function prepareProductData($product, $quantity)
    {
        return [
            'product_name' => $product->name,
            'product_qty' => $quantity,
            'product_materials' => []
        ];
    }

    // Calculate the required quantity of the material
    private function calculateRequiredQuantity($productQuantity, $material)
    {
        return $productQuantity * $material->pivot->quantity;
    }

    // Allocate material from warehouses
    private function allocateMaterial($material, $requiredQuantity, $warehouses, &$allocatedMaterials)
    {
        $allocated = [];

        // Find relevant warehouses for the material
        $materialWarehouses = $warehouses->where('material_id', $material->id);

        foreach ($materialWarehouses as $warehouse) {
            if ($requiredQuantity <= 0) {
                break; // Stop if no more material is required
            }

            // Check how much of this material has already been allocated from this warehouse
            $alreadyAllocated = $allocatedMaterials[$material->id][$warehouse->id] ?? 0;

            $available = $warehouse->remainder - $alreadyAllocated;

            if ($available <= 0) {
                continue; // No material available in this warehouse
            }

            $taken = min($requiredQuantity, $available);
            $allocated[] = $this->createMaterialAllocationResponse($warehouse, $material, $taken);

            // Update the allocated quantity for this material in this warehouse
            $allocatedMaterials[$material->id][$warehouse->id] = ($allocatedMaterials[$material->id][$warehouse->id] ?? 0) + $taken;

            $requiredQuantity -= $taken;
        }

        // If there's still a shortage, return the remaining quantity with null for warehouse
        if ($requiredQuantity > 0) {
            $allocated[] = $this->createMaterialAllocationResponse(null, $material, $requiredQuantity, null);
        }

        return $allocated;
    }

    // Create the material allocation response structure
    private function createMaterialAllocationResponse($warehouse, $material, $quantity, $price = null)
    {
        return [
            'warehouse_id' => $warehouse ? $warehouse->id : null,
            'material_name' => $material->name,
            'qty' => $quantity,
            'price' => $price ?? $warehouse?->price,
        ];
    }

    // Get all warehouses for materials related to the products
    private function getWarehouses($products)
    {
        $materialIds = collect($products)
            ->pluck('product_id')
            ->map(fn($productId) => Product::find($productId)->materials->pluck('id'))
            ->flatten()
            ->unique();

        return Warehouse::whereIn('material_id', $materialIds)->get();
    }

    // Handle product not found error
    private function handleProductNotFound($productId)
    {
        return [
            'error' => "Product with ID {$productId} not found",
        ];
    }
}

