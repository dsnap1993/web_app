<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\WebAPI\WebAPI;
use Log;

class ChangePasswdController extends Controller
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
     * Show change password page
     * 
     * @param   Request     $request
     * @return  \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        return view('change_password.change_password');
    }

    /**
     * Update password
     * 
     * @param   Request    $request
     * @return  \Illuminate\Contracts\Support\Renderable
     */
    public function update(Request $request)
    {
        if ($request->input('password') !== $request->input('confirm_passwd')) {
            $errMsg = config('messages.error.change_password.diff');
            $request->session()->flash('message', $errMsg);
            return redirect()->back();
        }
        $apiPath = config('api.ver') . config('api.users');
        $requestParams = array(
            'user_id' => $request->session()->get('user_id'),
            'name' => $request->session()->get('name'),
            'email' => $request->session()->get('email'),
            'password' => $request->input('password'),
        );

        // call API PUT /users
        $webApi = new WebAPI;
        $response = $webApi->callPutAPI($requestParams, $apiPath);
        $array = json_decode($response['body'], true);
        Log::debug(__METHOD__ . ' array = ' . print_r($array, true));

        if ($response['statusCode'] === 200) {
            $request->session()->put('password', $array['password']);
            return redirect()->to('/dashboard');
        } else {
            $errMsg = config('messages.error.dashboard.fail');
            $request->session()->flash('message', $errMsg);
            return redirect()->back();
        }
    }
}