<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\WebAPI\WebAPI;
use Log;

class CreateUserController extends Controller
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
     * Show register page
     * 
     * @param   Request     $request
     * @return  \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        return view('auth.register');
    }

    /**
     * Create a new user's data
     * 
     * @param   Request    $request
     * @return  \Illuminate\Contracts\Support\Renderable
     */
    public function store(Request $request)
    {
        $apiPath = config('api.ver') . config('api.users');
        $requestParams = array(
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        );

        // call API POST /users.json
        $webApi = new WebAPI;
        $response = $webApi->callAPI($requestParams, 'POST', $apiPath);
        Log::debug(__METHOD__ . ' response = ' . print_r($response, true));

        // set response data in session
        $array = json_decode($response['body'], true);
        Log::debug(__METHOD__ . ' array = ' . print_r($array, true));

        if ($response['statusCode'] === 201) {
            // set response data in session
            $request->session()->put('user_id', $array['user_id']);
            $request->session()->put('email', $array['email']);
            $request->session()->put('name', $array['name']);
            $request->session()->put('password', $requestParams['password']);
            return redirect()->to('/dashboard');
        } else {
            $errMsg = config('messages.error.users.fail');
            $request->session()->flash('message', $errMsg);
            return view('auth.register', $errMsg);
        }
    }
}
