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
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->apiPath = '/users';
    }

    public function show()
    {
        return view('auth.login');
    }

    public function postLogin(Request $request)
    {
        $apiPath = '/users'; // use the value in config
        $requestParams = $request->all();
        unset($requestParams['_token']);

        $webApi = new WebAPI;
        $response = $webApi->callAPI($requestParams, 'GET', $apiPath);
        Log::debug(__METHOD__ . ' response = ' . print_r($response, true));
    
        if ($response['statusCode'] == 200) {
            return redirect()->route('dashboard');
        } else if ($response['statusCode'] == 401) {
            $errMsg = config('messages.error.login.fail');
            return redirect()->back()->withErrors($errMsg);
        } else if ($response['statusCode'] == 403) {
            $errMsg = config('messages.error.login.locked');
            return redirect()->back()->withErrors($errMsg);
        } else {
            $errMsg = config('messages.error.login.other');
            return redirect()->back()->withErrors($errMsg);
        }
    }
}
