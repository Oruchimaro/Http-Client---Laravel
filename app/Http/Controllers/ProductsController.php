<?php

namespace App\Http\Controllers;

class ProductsController extends Controller
{
    /**
     * Show details of a product
     */
    public function show($title, $id)
    {
        $product = $this->marketService->getProduct($id);

        return view('products.show')
            ->with([
                'product' => $product
            ]);
    }
}
