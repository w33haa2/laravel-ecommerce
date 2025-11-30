<?php

namespace App\Actions;

use App\Models\Product;

class ListProductsAction
{
    /**
     * Get paginated list of products.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function execute()
    {
        return Product::paginate(10);
    }
}

