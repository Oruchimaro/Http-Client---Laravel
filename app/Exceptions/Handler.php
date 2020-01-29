<?php

namespace App\Exceptions;

use Exception;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ClientException) {
            return $this->handleClientException($exception, $request);
        }

        return parent::render($request, $exception);
    }

    /**
     * Handle the exceptions correctly when sending requests
     * @return Illuminate\Http\Response
     */
    protected function handleClientException($exception, $request)
    {
        $code = $exception->getCode();

        $response = json_decode($exception->getResponse()->getBody()->getContents());

        $errMsg = $response->error;

        switch ($code) {
            case Response::HTTP_UNAUTHORIZED:
                $request->session()->invalidate();

                if ($request->user()) {
                    auth()->logout();

                    return redirect()
                        ->route('welcome')
                        ->withErrors(['message' => 'The authentication failed. Please login again!!!']);
                }

                abort(500, 'Error authenticating the request. Try again later.');
                break;
            default:
                return redirect()->back()->withErrors(['message' => $errMsg]);
                break;
        }
    }
}
