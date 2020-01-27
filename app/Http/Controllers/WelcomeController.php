<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Show the app dashboard/landing page
     */
    public function show()
    {
        $products = $this->marketService->getProducts();
        $categories = $this->marketService->getCategories();

        return view('welcome')
            ->with([
                'products' => $products,
                'categories' => $categories
            ]);
    }
}
