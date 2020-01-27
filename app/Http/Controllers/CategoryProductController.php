<?php

namespace App\Http\Controllers;

class CategoryProductController extends Controller
{
    /**
     * Show products of a category
     */
    public function show($title, $id)
    {
        $products = $this->marketService->getCategoryProducts($id);

        return view('categories.products.show')
            ->with([
                'products' => $products
            ]);
    }
}
