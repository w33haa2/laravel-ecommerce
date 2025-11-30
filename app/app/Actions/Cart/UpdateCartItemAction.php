<?php

namespace App\Actions\Cart;

use App\Models\CartItem;
use Illuminate\Contracts\Auth\Authenticatable;

class UpdateCartItemAction
{
    /**
     * Update the quantity of a cart item.
     *
     * @param  Authenticatable  $user
     * @param  string|int  $cartItemId
     * @param  int  $quantity
     * @return CartItem
     */
    public function execute(Authenticatable $user, string|int $cartItemId, int $quantity): CartItem
    {
        $item = CartItem::where('user_id', $user->id)
            ->where('id', $cartItemId)
            ->firstOrFail();

        $item->update(['quantity' => $quantity]);

        return $item;
    }
}

