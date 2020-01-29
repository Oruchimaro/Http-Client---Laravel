<?php

namespace App\Http\Controllers;

use App\Services\MarketService;
use Illuminate\Http\Request;

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
     * Show the form to create a product on the API
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showPublishProductForm()
    {
        $categories = $this->marketService->getCategories();

        return view('products.publish')
            ->with(['categories' => $categories]);
    }

    /**
     * Create the product on the AP
     * @return \Illuminate\Http\Response
     */
    public function publishProduct(Request $request)
    {
        $rules = [
            'title' => 'required',
            'details' => 'required',
            'stock' => 'required|min:1',
            'picture' => 'required|image',
            'category' => 'required'
        ];

        $productData = $this->validate($request, $rules);

        $productData['picture'] = fopen($request->picture->path(), 'r');

        $productData = $this->marketService->publishProduct($request->user()->service_id, $productData);

        $this->marketService->setProductCategory($productData->identifier, $request->category);

        $this->marketService->updateProduct($request->user()->service_id, $productData->identifier, [
            'situation' => 'available'
        ]);

        return redirect()
            ->route('products.show', [
                'title' => $productData->title,
                'id' => $productData->identifier
            ])
            ->with('success', ['Product created successfully !']);
    }


    /**
     * Enable to purchase a product from the API
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function purchaseProduct(Request $request, $title, $id)
    {
        $this->marketService->purchaseProduct($id, $request->user()->service_id, 1);

        return redirect()
            ->route('products.show', [
                'title' => $title,
                'id' => $id
            ])
            ->with('success', ['Product Purchased Successfully !!']);
    }
}
