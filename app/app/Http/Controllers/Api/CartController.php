<?php

namespace App\Http\Controllers\Api;

use App\Actions\Cart\AddCartItemAction;
use App\Actions\Cart\ListCartItemsAction;
use App\Actions\Cart\RemoveCartItemAction;
use App\Actions\Cart\UpdateCartItemAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCartItemRequest;
use App\Http\Requests\Api\UpdateCartItemRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * List all cart items for the authenticated user.
     */
    public function index(
        Request $request,
        ListCartItemsAction $listCartItemsAction
    ): JsonResponse {
        $items = $listCartItemsAction->execute($request->user());

        return response()->json($items);
    }

    /**
     * Add an item to the cart.
     */
    public function store(
        StoreCartItemRequest $request,
        AddCartItemAction $addCartItemAction
    ): JsonResponse {
        $item = $addCartItemAction->execute($request->user(), $request->validated());

        return response()->json($item, 201);
    }

    /**
     * Update a cart item quantity.
     */
    public function update(
        UpdateCartItemRequest $request,
        UpdateCartItemAction $updateCartItemAction,
        string $id
    ): JsonResponse {
        $item = $updateCartItemAction->execute(
            $request->user(),
            $id,
            $request->validated()['quantity']
        );

        return response()->json($item);
    }

    /**
     * Remove an item from the cart.
     */
    public function destroy(
        Request $request,
        RemoveCartItemAction $removeCartItemAction,
        string $id
    ): JsonResponse {
        $removeCartItemAction->execute($request->user(), $id);

        return response()->json(['message' => 'Item removed']);
    }
}
