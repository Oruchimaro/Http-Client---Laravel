<?php

namespace App\Services;

use App\Traits\ConsumeExternalServices;
use App\Traits\InteractWithMarketResponses;

class MarketAuthenticationService
{
    use ConsumeExternalServices, InteractWithMarketResponses;

    /** The URL to send the request 
     *  Client Id to identify the client in the API
     *  Client Secret to identify the client in the API
     *  Client Id to identify the password client in the API
     *  Client Secret to identify the password client in the API
     *  @var string
     */
    protected $baseUri;
    protected $clientId;
    protected $clientSecret;
    protected $passwordClientId;
    protected $passwordClientSecret;


    /**  */
    public function __construct()
    {
        $this->baseUri = config('services.market.base_uri');
        $this->clientId = config('services.market.client_id');
        $this->clientSecret = config('services.market.client_secret');
        $this->passwordClientId = config('services.market.password_client_id');
        $this->passwordClientSecret = config('services.market.password_client_secret');
    }


    /**
     * Obtain an access token associated with the client
     * @return stdClass 
     */
    public function getClientCredentialsToken()
    {
        if ($token = $this->existingValidToken()) {
            return $token;
        }

        $formParams = [
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret
        ];

        $tokenData = $this->makeRequest('POST', 'oauth/token', [], $formParams);

        $this->storeValidToken($tokenData, 'client_credentials');

        return $tokenData->access_token;
    }



    /**
     * Store a valid token with some attributes
     * @return void
     */
    public function storeValidToken($tokenData, $grantType)
    {
        $tokenData->token_expires_at = now()->addSeconds($tokenData->expires_in - 5);

        $tokenData->access_token = "{$tokenData->token_type} {$tokenData->access_token}";

        $tokenData->grant_type = $grantType;

        session()->put(['current_token' => $tokenData]);
    }


    /**
     * Verify if there is any valid token on session
     * @return string/boolean
     */
    public function existingValidToken()
    {
        if (session()->has('current_token')) {
            $tokenData = session()->get('current_token');

            if (now()->lt($tokenData->token_expires_at)) {
                return $tokenData->access_token;
            }
        }

        return false;
    }


    /**
     * Generate the URL to obtain user authorization
     * @return string
     */
    public function resolveAuthorizationUrl()
    {
        $query = http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => route('authorization'),
            'response_type' => 'code',
            'scope' => 'purchase-product manage-products manage-account read-general'
        ]);


        return "{$this->baseUri}oauth/authorize?{$query}";
    }


    /**
     * Obtain an access token  from a given code
     * @return stdClass 
     */
    public function getCodeToken($code)
    {
        $formParams = [
            'grant_type' => 'authorization_code',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => route('authorization'),
            'code' => $code
        ];

        $tokenData = $this->makeRequest('POST', 'oauth/token', [], $formParams);

        $this->storeValidToken($tokenData, 'authorization_code');

        return $tokenData;
    }
}
