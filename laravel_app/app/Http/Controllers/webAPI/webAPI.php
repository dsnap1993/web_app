<?php

namespace App\Http\Controllers\WebAPI;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Log;

class WebAPI
{
    /**
     * Call API
     * 
     * @param   $request    a http resuest with json format
     * @param   $method     http method
     * @param   $path       a path of API
     * @return  
     */
    public function callAPI($request, $method, $path)
    {
        $client = new Client([
            'base_uri' => env('API_URL'), 
            'timeout' => env('API_TIMEOUT')
        ]);

        try {
            Log::info(__METHOD__ . '[Call API]' . $method . ' /'. $path);
            Log::info(__METHOD__ . 'Request Parameters: ' . $request);
            $response = $client->request(
                $method,
                $path,
                ['json' => $request]
            );
            Log::info(__METHOD__ . 'Response Parameters: ' . $response);
            return $response;
        } catch(RequestException $e) {
            Log::info(__METHOD__ . 'Exception[Request]: ' . Psr7\str($getRequest()));
            if ($e->hasResponse()) {
                Log::info(__METHOD__ . 'Exception[Response]: ' . Psr7\str($getResponse()));
                $errMsg = Config::get('messages.error.login.other');
                return redirect()->action('ActionIndex')->withErrors($errmsg);
            }
        }
    }
}