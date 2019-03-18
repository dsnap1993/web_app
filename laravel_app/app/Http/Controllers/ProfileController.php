<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\WebAPI\WebAPI;
use Log;

class ProfileController extends Controller
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
     * Show profile page
     * 
     * @param   Request     $request
     * @return  \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $data = array(
            'email' => $request->session()->get('email'),
            'name' => $request->session()->get('name'),
        );
        return view('profile.profile', compact('data'));
    }

    /**
     * Update profile
     * 
     * @param   Request    $request
     * @return  \Illuminate\Contracts\Support\Renderable
     */
    public function update(Request $request)
    {
        $apiPath = config('api.ver') . config('api.users');
        $requestParams = array(
            'user_id' => $request->session()->get('user_id'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->session()->get('password'),
        );

        // call API PUT /users.json
        $webApi = new WebAPI;
        $response = $webApi->callAPI($requestParams, 'PUT', $apiPath);
        Log::debug(__METHOD__ . ' response = ' . print_r($response, true));

        // set response data in session
        $array = json_decode($response['body'], true);
        Log::debug(__METHOD__ . ' array = ' . print_r($array, true));

        if ($response['statusCode'] === 200) {
            $request->session()->put('email', $array['email']);
            $request->session()->put('name', $array['name']);
            return redirect()->to('/dashboard');
        } else {
            $errMsg = config('messages.error.dashboard.fail');
            $request->session()->flash('message', $errMsg);
            return redirect()->back();
        }
    }
}