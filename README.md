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
