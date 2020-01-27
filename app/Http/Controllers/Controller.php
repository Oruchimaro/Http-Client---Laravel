<?php

namespace App\Http\Controllers;

use App\Services\MarketService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * all controllers are extending this, so we use dependency injection
     * to obtain an instance of MarketService and pass it to all controllers.
     */

    /** The market service to consume for this client
     * @var App\Services\MarketService
     */
    protected $marketService;


    public function __construct(MarketService $marketService)
    {
        $this->marketService = $marketService;
    }
}
