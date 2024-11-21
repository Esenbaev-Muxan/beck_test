<?php
namespace App\Http\Controllers;

use App\Services\ProductionService;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    protected $productionService;

    public function __construct(ProductionService $productionService)
    {
        $this->productionService = $productionService;
    }

    public function getMaterials(Request $request)
    {
        $products = $request->input('products');

        if (!is_array($products)) {
            return response()->json(['error' => 'Invalid products format'], 400);
        }

        $response = $this->productionService->getMaterialsForProducts($products);

        return response()->json($response);
    }
}
