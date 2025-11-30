<?php

namespace App\Actions;

use App\Models\Product;

class GetProductAction
{
    /**
     * Get a product by ID.
     *
     * @param  string|int  $id
     * @return Product
     */
    public function execute(string|int $id): Product
    {
        return Product::findOrFail($id);
    }
}

