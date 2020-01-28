<?php

namespace App\Http\Controllers;

use App\Services\MarketService;

class ProductsController extends Controller
{
    /**
     * Create a new controller instance
     * @return void
     */
    public function __construct(MarketService $marketService)
    {
        $this->middleware('auth')->except(['show']);

        parent::__construct($marketService);
    }


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


    /**
     * Enable to purchase a product from the API
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function purchaseProduct()
    {
    }

    /**
     * Show the form to create a product on the API
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showPublishProductForm()
    {
    }

    /**
     * Create the product on the AP
     * @return \Illuminate\Http\Response
     */
    public function publishProduct()
    {
    }
}
