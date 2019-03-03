<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\WebAPI\WebAPI;
use Log;

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
    protected $redirectTo = '/';
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
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
            return redirect()->to('/dashboard');
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