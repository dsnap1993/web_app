<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\WebAPI\WebAPI;
use Log;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function show()
    {
        return view('auth.login');
    }

    /**
     * Execute Logging in.
     * @params  Request $request
     * @return  \Illuminate\Contracts\Support\Renderable
     */
    public function postLogin(Request $request)
    {
        $apiPath = config('api.users');
        $requestParams = $request->all();
        unset($requestParams['_token']);

        $webApi = new WebAPI;
        $response = $webApi->callAPI($requestParams, 'GET', $apiPath);
        Log::debug(__METHOD__ . ' response = ' . print_r($response, true));

        if ($response['statusCode'] == 200) {
            $user_id =$this->getUserId($response);
            return redirect()->to('dashboard/');
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

    /**
     * Get user_id from $response.
     * @params  array   $response   response data from API
     * @return  int
     */
    private function getUserId($response)
    {
        $arrayResponse = json_decode($response['body'], true);
        $user_id = $arrayResponse['user_id'];
        return $user_id;
    }
}
