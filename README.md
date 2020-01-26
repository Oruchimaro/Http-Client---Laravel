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
