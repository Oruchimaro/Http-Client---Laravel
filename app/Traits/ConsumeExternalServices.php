<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait ConsumeExternalServices
{
    /**
     * Send a request to any service
     * @return stdClass/string
     */
    public function makeRequest($method, $requestUrl, $queryParams = [], $formParams = [], $headers = [])
    {

        $client = new Client([
            'base_uri' => $this->baseUri, //this baseUri comes from the class that uses this trait
        ]);


        if (method_exists($this, 'resolveAuthorization')) {

            $this->resolveAuthorization($queryParams, $formParams, $headers); //from class that uses this
        }


        $response = $client->request($method, $requestUrl, [
            'query' => $queryParams,
            'form_params' => $formParams,
            'headers' => $headers
        ]);


        $response = $response->getBody()->getContents();


        if (method_exists($this, 'decodeResponse')) {
            $this->decodeResponse($response); //from class that uses this
        }


        if (method_exists($this, 'checkIfErrorResponse')) {
            $this->checkIfErrorResponse($response); //from class that uses this
        }


        return $response;
    }
}
