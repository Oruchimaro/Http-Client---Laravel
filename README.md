## What is this ?
    this is a simple projject for testing consuming http client APIs .
    We are using "https://laravelapi.juandmegon.com/" as a source for data and 
    Oauth provider.

## How to use this ?
    clone the project.
    create a databse .
    cd into cloned directory
    $ composer install
    $ cp .env.example .env
    edit the database credentials in .env file
    $ php artisan migrate
    $ php artisan key:generate
    $ npm install
    $ npm run dev


## Documents 
### 0.lets work with API
    in our api we will create 2 users(register).

    First one can be used to create a OAuth client.
    Second one will have a Personal Access Token.
### 1.Guzzle
    we are using guzzlehttp package to send http requests to external API.
    you can find the guzzle documentation at "http://docs.guzzlephp.org/en/stable/"
    $ composer require guzzlehttp/guzzle

### 2.Change user table and model
    we are going to get our users from an external API so we need to make our
    user table compatible for it.instead of using the email and password we are
    storing tokens that we authenticate fro our API and other related stuff like
    expire time or grant type.

### 3. Disable Register
    As we are using external API for sign up/in we dont need to register users
    in our app, we can disable them with passing the options to the route file.
    look at auth facade for more info.

### 4.Making an http request
    We are going to create a trait to be used by any controller or class, to
    enable it to send any http request of anytype with any query param, to any
    service .This is cool!!!

    First add a new file to the directory within app,
    app/Traits/ConsumeExternalServices.php

    in this trait we are going to have a single method
    this method will create a instance of guzzle Client, and gets some
    parameters
    like method, requestUri, queryParams, formParams,headers that will be used 
    to make the request.

    in this trait we will use a attribute called baseUri, this uri comes from
    the class, model or controler that is using this trait, if it is not present
    the guzzle will automatically calculate the request from the url that we
    specify  sending there.if it is present all the urls request will be
    calculated from there.


    then we can have this trait handle or resolve authorization , so in any
    class that is using this trait we will have a resolveAuthorization() method,
    and pass them the queryParams, formParams and headers to handle it.

    then after sending request we can get a response from the service that will
    be stored in response variable.

    and then return the response.

    The class that uses this trait may have  method to decode the response and
    check if the errors are present on the response.


### 5.Create a service to use the trait .
    here want to make a service to use the trait to consume an external service.
    app/Services/MarketService.php 

    now this file may containt the methods that specified in trait as optional
    methods.

    the first one to resolveAuthorization will take the parameters and refrence
    the actual ones for any change to take effect 

    the second one decodeResponse is responsible for finding what tyoe of
    response the service is returning(yaml, hsin,...)

    the third one will proccess the errors sent from the service if there are
    any

    Any other Service that we need to integrate with our app can have a Seprate
    file and the basic layout will be like this one.

### 6. configure project to use external service
    we will add these keys to .env file

    MARKET_BASE_URI=
    MARKET_CLIENT_ID=
    MARKET_CLIENT_SECRET=
    MARKET_PASSWORD_CLIENT_ID=
    MARKET_PASSWORD_CLIENT_SECRET=

    Then in config/services.php file we will egt these values for later useage.
    we will add a new service called Market and add them, after that we will be
    able to use these anywhere in the project.

    'market' => [...],

    Now we need the MarketService class to use this base_uri,so we can add that
    to a constructor in it.

### 7.Modify header for request
    Here we can get a Personal access-token from our api  for our second user, then in MarketService
    resolveAuthorization method we attach it to headers.
    we will add this token to authorization header key/value as it is shown.
    later we will automate this proccess, but for now this will do.

    we attach the token to header with this

    ```PHP 
        $headers['Authorization'] = 'Bearer access-token';
    ```

    Now we are able to authorize a request by sending this access-token with
    request headers.

### 8.Now we want to automate getting the access token
    First signin to the API with first user that you created.go to MyClients
    section and copy the credentials of the client that you created 
    and fill in the MARKET_CLIENT_ID and MARKET_CLIENT_SECRET fields in
    env file.

    now that we have these we are able to send a request to obtain a vaid access
    token for this client credentials.

    So create a service MarketAuthenticationService 
    and add the getClientCredentialsToken() method to it to perfoem a request.
    this method will get a freash access token and returnit 

    now use this service(class) in AuthorizesMarketRequests trait.
    now if we test the project we can see that every thing works as before and 
    the proccess of getting an access token for client requests are automatic.

    Now the problem is that we are obtaining a fresh access-token with every
    request, not so good !!!. we want to use the toke untill it expires.

    If we go to services/MarketAuthenticationService , there we can add methods
    to store the access token in a session and use it for all requests untill it
    expires.

    then in getClientCredentialsToken method check to see if there is a token in
    session, if not create one and store it in session.

    the methods are storeValidToken() and existingValidToken().


### 9.generating the URL to enable the login button with the API
    In MarketAuthenticationService add a new method resolveAuthorizationUrl() to
    build query params to be attached to url, because when you click a link it
    will be a get request to url.

    then we create a route for authorize and then use the method that we bulid
    in login controller .

