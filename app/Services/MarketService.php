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
}
