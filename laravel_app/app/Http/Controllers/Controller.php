<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Http\Requests;
use Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Check whether logging in
     * 
     * @param   Request  $request
     * @param   $path   a path of redirected page
     * @return  boolean
     */
    protected function checkLoggingIn($request)
    {
        $sessionData = $request->session()->all();
        Log::debug(__METHOD__ . ' sessionData = ' . print_r($sessionData, true));
        $result = $this->formatSessionData($sessionData);
        Log::debug(__METHOD__ . ' result = ' . print_r($result, true));
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Format array of session data
     * 
     * @param   array   $sessionData
     * @return  array
     */
    private function formatSessionData($sessionData)
    {
        unset($sessionData['_token']);
        unset($sessionData['_previous']);
        unset($sessionData['_flash']);

        return $sessionData;
    }
}
