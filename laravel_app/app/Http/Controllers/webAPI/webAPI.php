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
     * @return  $response
     */
    public function callAPI($request, $method, $path)
    {
        $client = new Client([
            'base_uri' => env('API_URL'), 
            'timeout' => env('API_TIMEOUT')
        ]);

        try {
            Log::info('Call API >>' . $method . '/'. $path);
            Log::info('Request Parameters: ' . $request);
            $response = $client->request(
                $method,
                $path,
                ['json' => $request]
            );
            Log::info('Response Parameters: ' . $response);
            return $response;
        } catch(RequestException $e) {
            Log::info('Exception[Request]: ' . Psr7\str($getRequest()));
            if ($e->hasResponse()) {
                Log::info('Exception[Response]: ' . Psr7\str($getResponse()));
            }
        }
    }
}