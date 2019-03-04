<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
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
        $requestParams =array();
        $apiPath = config('api.users');

        // create request params
        $requestParams['email'] = $request->input('email');
        //$requestParams['password'] = Crypt::encryptString($request->input('passwd'));
        $requestParams['password'] = $request->input('password');

        // call API GET /users
        $webApi = new WebAPI;
        $response = $webApi->callAPI($requestParams, 'GET', $apiPath);
        Log::debug(__METHOD__ . ' response = ' . print_r($response, true));

        // set response data in session
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