<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\WebAPI\WebAPI;
use GuzzleHttp\Client;
use Log;

class ActionLogin extends Controller
{
    private $apiPath;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->apiPath = '/users.json'; // provisional path
    }

    public function __invoke(Request $request)
    {
        $jsonRequest = $this->encodeToJson($request);
        $webApi = new WebAPI();

        $response = $webApi->callAPI($jsonRequest, 'POST', $apiPath);
        $statusCode = $response->getStatusCode();
        Log::info(__METHOD__ . 'statusCode = ' . $statusCode);
    
        if ($statusCode == 200) {
            return redirect()->route('dashboard');
        } else if ($statusCode == 500) {
            $errMsg = Config::get('messages.error.login.fail');
            return redirect()->back()->withErrors($errmsg);
        } else if ($statusCode == 403) {
            $errMsg = Config::get('messages.error.login.locked');
            return redirect()->back()->withErrors($errmsg);
        } else {
            $errMsg = Config::get('messages.error.login.other');
            return redirect()->back()->withErrors($errmsg);
        }
    }
}
