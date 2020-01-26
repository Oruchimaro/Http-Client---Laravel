<?php

namespace App\Services;

use App\Traits\ConsumeExternalServices;

class MarketService
{
    use ConsumeExternalServices;

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
     * Resolve the elements to send when authorizing the request
     * @return void
     */
    public function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        //
    }


    /**
     * Decode correspondingly the response
     * @return stdClass 
     */
    public function decodeResponse($response)
    {
        //
    }


    /**
     * Resolve when the request failed
     * @return void
     */
    public function checkIfErrorResponse($response)
    {
        //
    }
}
