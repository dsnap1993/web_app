<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\WebAPI\WebAPI;
use GuzzleHttp\Client;
use Log;

class LoginAction extends Controller
{
    private $apiPath;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->apiPath = '/users'; // provisional path
    }

    public function show()
    {
        return view('auth.login');
    }

    public function postLogin(Request $request)
    {
        $jsonRequest = $this->encodeToJson($request);

        $response = WebAPI::callAPI($jsonRequest, 'GET', $apiPath);
        $statusCode = $response->getStatusCode();
        Log::info(__METHOD__ . 'statusCode = ' . $statusCode);
    
        if ($statusCode == 200) {
            return redirect()->route('dashboard');
        } else if ($statusCode == 401) {
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
