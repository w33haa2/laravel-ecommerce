<?php

namespace App\Actions\Cart;

use App\Models\CartItem;
use Illuminate\Contracts\Auth\Authenticatable;

class AddCartItemAction
{
    /**
     * Add an item to the cart or increment quantity if it already exists.
     *
     * @param  Authenticatable  $user
     * @param  array<string, mixed>  $data
     * @return CartItem
     */
    public function execute(Authenticatable $user, array $data): CartItem
    {
        // Check if item already exists
        $item = CartItem::where('user_id', $user->id)
            ->where('product_id', $data['product_id'])
            ->first();

        if ($item) {
            // Item exists, increment quantity
            $item->increment('quantity', $data['quantity']);
            $item->refresh();

            return $item;
        }

        // Item doesn't exist, create new one
        return CartItem::create([
            'user_id' => $user->id,
            'product_id' => $data['product_id'],
            'name' => $data['name'],
            'price' => $data['price'],
            'quantity' => $data['quantity'],
        ]);
    }
}

