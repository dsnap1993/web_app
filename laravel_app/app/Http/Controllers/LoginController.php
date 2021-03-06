<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
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
     * @param  LoginRequest $request
     * @return  \Illuminate\Contracts\Support\Renderable
     */
    public function postLogin(LoginRequest $request)
    {
        $request->session()->flush();
        $apiPath = config('api.ver') . config('api.login');

        // create request params
        $requestParams = array(
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        );

        // call API POST /login
        $webApi = new WebAPI;
        $response = $webApi->callPostAPI($requestParams, $apiPath);
        $array = json_decode($response['body'], true);
        Log::debug(__METHOD__ . ' array = ' . print_r($array, true));

        // redirect page
        switch ($response['statusCode']) {
            case 200:
                // set response data in session
                $request->session()->put('user_id', $array['user_id']);
                $request->session()->put('email', $array['email']);
                $request->session()->put('name', $array['name']);
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