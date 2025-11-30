<?php

namespace App\Http\Controllers;

use App\Actions\PlaceOrderAction;
use App\Http\Requests\PlaceOrderRequest;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

class CheckoutController extends Controller
{
    /**
     * Place a new order.
     */
    public function placeOrder(
        PlaceOrderRequest $request,
        PlaceOrderAction $placeOrderAction
    ): JsonResponse {
        $order = $placeOrderAction->execute($request->validated());

        return response()->json($order, 201);
    }

    /**
     * Get order details.
     */
    public function getOrder(string $id): JsonResponse
    {
        $order = Order::with('items')->findOrFail($id);

        return response()->json($order);
    }
}
