<?php

namespace App\Traits;

trait InteractWithMarketResponses
{
    /**
     * Decode correspondingly the response
     * @return stdClass 
     */
    public function decodeResponse($response)
    {
        $decoded_response = json_decode($response);
        //if there is data return it, if not return the response
        return $decoded_response->data ?? $decoded_response;
    }


    /**
     * Resolve when the request failed
     * @return void
     */
    public function checkIfErrorResponse($response)
    {
        //here we check if the code is200 but there is an error
        if (isset($response->error)) {
            throw new \Exception("something went weong: {$response->error}");
        }
    }
}
