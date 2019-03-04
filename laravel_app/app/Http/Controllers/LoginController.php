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
    }

    /**
     * Show login page.
     * 
     * @return  \Illuminate\Contracts\Support\Renderable
     */
    public function show()
    {
        return view('auth.login');
    }

    /**
     * Execute Logging in.
     * 
     * @param  Request $request
     * @return  \Illuminate\Contracts\Support\Renderable
     */
    public function postLogin(Request $request)
    {
        $apiPath = config('api.users');
        $requestParams = $request->all();
        unset($requestParams['_token']);

        // call API GET /users
        $webApi = new WebAPI;
        $response = $webApi->callAPI($requestParams, 'GET', $apiPath);
        Log::debug(__METHOD__ . ' response = ' . print_r($response, true));

        // set response data in session
        $array = json_decode($response['body'], true);
        Log::debug(__METHOD__ . ' array = ' . print_r($array, true));

        // set response data in session
        $request->session()->put('user_id', $array['user_id']);
        $request->session()->put('email', $array['email']);
        $request->session()->put('name', $array['name']);

        // redirect page
        switch ($response['statusCode']) {
            case 200:
                return redirect()->to('/dashboard');
            case 401:
                $errMsg = config('messages.error.login.fail');
                $request->session()->flash('message', $errMsg);
                return redirect()->back();
            case 403:
                $errMsg = config('messages.error.login.locked');
                $request->session()->flash('message', $errMsg);
                return redirect()->back();
            default:
                $errMsg = config('messages.error.login.other');
                $request->session()->flash('message', $errMsg);
                return redirect()->back();
        }
    }
}