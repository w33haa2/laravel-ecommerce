<?php

namespace App\Actions\Cart;

use App\Models\CartItem;
use Illuminate\Contracts\Auth\Authenticatable;

class RemoveCartItemAction
{
    /**
     * Remove an item from the cart.
     *
     * @param  Authenticatable  $user
     * @param  string|int  $cartItemId
     * @return bool
     */
    public function execute(Authenticatable $user, string|int $cartItemId): bool
    {
        return CartItem::where('user_id', $user->id)
            ->where('id', $cartItemId)
            ->delete() > 0;
    }
}

