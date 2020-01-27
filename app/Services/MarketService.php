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
        $accessToken = $this->resolveAccessToken();

        $headers['Authorization'] = $accessToken;
    }


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


    public function resolveAccessToken()
    {
        return 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjU2ODAzMDg3ZjY4ZjMzZjg0MGQyNTVhYTU3YmE0NDdmYTgyMGE0YThjMDYzNWY4ZDJjNjc0N2U3MjcwMzZlZjg4YTExMjQ5NDQ1ODYxMWI5In0.eyJhdWQiOiIyIiwianRpIjoiNTY4MDMwODdmNjhmMzNmODQwZDI1NWFhNTdiYTQ0N2ZhODIwYTRhOGMwNjM1ZjhkMmM2NzQ3ZTcyNzAzNmVmODhhMTEyNDk0NDU4NjExYjkiLCJpYXQiOjE1ODAxMDIwOTMsIm5iZiI6MTU4MDEwMjA5MywiZXhwIjoxNjExNzI0NDkzLCJzdWIiOiIxMjA4Iiwic2NvcGVzIjpbInB1cmNoYXNlLXByb2R1Y3QiLCJtYW5hZ2UtcHJvZHVjdHMiLCJtYW5hZ2UtYWNjb3VudCIsInJlYWQtZ2VuZXJhbCJdfQ.kPvHnDSQIcEqcf1z6Z2M3wmosYoP1XZriuK0tDykgoWdbA-yxm4TliOlD1qpSCAzGouAifL1a9Co6mNLe8BkdX1w_XLd5GslzsTpTz6HOdDLQR6kZXMafEg66FFPnaWMuEMCShkYb96cwTxMtYK29D_rFtA5cHpFDJVQKUNdFCQ_Oc0U9nJwfkjUdaOfgpUi8Po9W6Y2hHfWR7CcZH7v6BTc_CVOhPYFKrXdXwT1v5osScVowvk6fMYc9pyRVrR_azwvteQQ0o_o8JlPkUxo88D4FH6y07NqNX9pOR0_CurSi308P-yV8TCbenQ1BrLLKZxr0f9lO_gyL0g70HHpAMcTV007b9vlHfCs3rmqPUCBy8vNlm0WMWSZAzQqV-NZZ4UzoTaKqYmvFvxBTsEwmzri58S850Aycrv9Lvu4OmlJ268asRa_5LInpMYrrlbX1rl_UnSEL4gw2XciAiBUQuEFQy2vq3Ijmv1rH43tPD40eZKJyzOkqp0hzqf8_IhaHqtMHWtlNRRpezHPu0lMP8l_Udyj1Ch6OKRDQmrIaUHjLO_XHrw-QMOvvtiY9DtQqccJNTxlX2U9heIw1C5xyn-WWfPgKiIxJBFr4762jFYR4gqcxGFP_6-Lu8hIHCWlbd5m9OtgqRJm6wmgHYV9pKoU1q3XcG2Trll4EKqbJZw';
    }
}
