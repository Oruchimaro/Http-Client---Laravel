<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Services\MarketAuthenticationService;
use App\Services\MarketService;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function __construct(MarketAuthenticationService $marketAuthenticationService, MarketService $marketService)
    {
        $this->middleware('guest')->except('logout');

        $this->marketAuthenticationService = $marketAuthenticationService;

        parent::__construct($marketService);
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

            $userData = $this->marketService->getUserInformation();

            $user = $this->registerOrUpdateUser($userData, $tokenData);

            $this->loginUser($user);

            return redirect()->intended('home');
        }

        //if we didnt get the code it means user canceled
        return redirect()
            ->route('login')
            ->withErrors(['You canceled the authorization proccess !!!']);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        try {
            $tokenData = $this->marketAuthenticationService
                ->getPasswordToken($request->email, $request->password);
            $userData = $this->marketService->getUserInformation();

            $user = $this->registerOrUpdateUser($userData, $tokenData);

            $this->loginUser($user, $request->has('remember'));

            return redirect()->intended('home');
        } catch (\Exception $e) {
            $this->incrementLoginAttempts($request);

            return $this->sendFailedLoginResponse($request);
        }
    }



    /**
     * Create or update a user using information from the API
     * @return App\User
     */
    public function registerOrUpdateUser($userData, $tokenData)
    {
        return User::updateOrCreate(
            [
                'service_id' => $userData->identifier
            ],
            [
                'grant_type' => $tokenData->grant_type,
                'access_token' => $tokenData->access_token,
                'refresh_token' => $tokenData->refresh_token,
                'token_expires_at' => $tokenData->token_expires_at
            ]
        );
    }


    /**
     * Create a user session in the HttpClient
     * @return void
     */
    public function loginUser(User $user, $remember = true)
    {
        Auth::login($user, $remember);

        session()->regenerate();
    }
}
