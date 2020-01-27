<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Services\MarketAuthenticationService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;


    /**
     * The service to authenticate actions
     *
     * @var App\Services\MarketAuthenticationService
     */
    protected $marketAuthenticationService;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MarketAuthenticationService $marketAuthenticationService)
    {
        $this->middleware('guest')->except('logout');

        $this->marketAuthenticationService = $marketAuthenticationService;
    }


    /**
     * copied from the trait above
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $authorizationUrl = $this->marketAuthenticationService->resolveAuthorizationUrl();

        return view('auth.login')
            ->with(['authorizationUrl' => $authorizationUrl]);
    }


    /**
     * Resolve the user authorizations
     *
     * @return Response
     */
    public function authorization(Request $request)
    {
        if ($request->has('code')) {
            $tokenData = $this->marketAuthenticationService->getCodeToken($request->code);

            dd($tokenData);
            return;
        }

        //if we didnt get the code it means user canceled
        return redirect()
            ->route('login')
            ->withErrors(['You canceled the authorization proccess !!!']);
    }
}
