<?php

namespace App\Http\Controllers;

use App\Actions\GetProductAction;
use App\Actions\ListProductsAction;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * List all products with pagination.
     */
    public function index(ListProductsAction $listProductsAction): JsonResponse
    {
        $products = $listProductsAction->execute();

        return response()->json($products);
    }

    /**
     * Get a specific product.
     */
    public function show(string $id, GetProductAction $getProductAction): JsonResponse
    {
        $product = $getProductAction->execute($id);

        return response()->json($product);
    }
}
