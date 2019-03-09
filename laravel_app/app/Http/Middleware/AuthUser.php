<?php

namespace App\Http\Middleware;

//use Illuminate\Http\Request;
//use App\Http\Requests;
use App\Http\WebAPI\WebAPI;
use Closure;
use Log;

class AuthUser
{
    public function handle($request, Closure $next)
    {
        // check whether setting data on session
        if (!($request->session()->has('user_id'))) {
            $request->session()->flash('err_msg', 'Failed to authenticate');
            return redirect()->to('/');
        }

        // call API GET /users
        $webApi = new WebAPI;
        $apiPath = config('api.ver') . config('api.login');

        $requestParams = array(
            'email' => $request->session()->get('email'),
            'password' => $request->session()->get('password'),
        );
        $response = $webApi->callAPI($requestParams, 'POST', $apiPath);

        // redirect page
        if ($response['statusCode'] == 200) {
            return $next($request);
        } else {
            $request->session()->flash('msg_session_timeout', 'Session Time Out');
            return redirect()->to('/');
        }
    }
}