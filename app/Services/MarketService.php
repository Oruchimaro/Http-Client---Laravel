<?php

namespace App\Services;

use App\Traits\AuthorizesMarketRequests;
use App\Traits\ConsumeExternalServices;
use App\Traits\InteractWithMarketResponses;

class MarketService
{
    use ConsumeExternalServices, AuthorizesMarketRequests, InteractWithMarketResponses;

    /** The URL to send the request 
     *  @var string
     */
    protected $baseUri;


    /**  */
    public function __construct()
    {
        $this->baseUri = config('services.market.base_uri');
    }


    /**
     * Obtain the list of products from API
     * @request stdClass
     */
    public function getProducts()
    {
        return $this->makeRequest('GET', 'products');
    }


    /**
     * Obtain the list of categories from API
     * @request stdClass
     */
    public function getCategories()
    {
        return $this->makeRequest('GET', 'categories');
    }

    /**
     * Obtain the details of a product from API
     * @request stdClass
     */
    public function getProduct($id)
    {
        return $this->makeRequest('GET', "products/{$id}");
    }

    /**
     * Obtain the products of a category from API
     * @request stdClass
     */
    public function getCategoryProducts($id)
    {
        return $this->makeRequest('GET', "categories/{$id}/products");
    }


    /**
     * obtain user info from API
     * @return stdClass 
     */
    public function getUserInformation()
    {
        return $this->makeRequest('GET', 'users/me');
    }


    /**
     * Publish a product on the API
     * @return stdClass
     */
    public function publishProduct($sellerId, $productData)
    {
        return $this->makeRequest(
            'POST',
            "sellers/{$sellerId}/products",
            [],
            $productData,
            [],
            $hasFile = true
        );
    }


    public function setProductCategory($productId, $categoryId)
    {
        return $this->makeRequest(
            'PUT',
            "products/{$productId}/categories/{$categoryId}"
        );
    }
}
