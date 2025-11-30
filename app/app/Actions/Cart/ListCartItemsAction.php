<?php

namespace App\Actions\Cart;

use App\Models\CartItem;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;

class ListCartItemsAction
{
    /**
     * Get all cart items for the authenticated user.
     *
     * @param  Authenticatable  $user
     * @return Collection<int, CartItem>
     */
    public function execute(Authenticatable $user): Collection
    {
        return CartItem::where('user_id', $user->id)->get();
    }
}

