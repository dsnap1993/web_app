<?php
namespace App\Http\WebAPI;

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
     * @param   $request    an array of request params
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

            Log::info(__METHOD__ . ' [Call API]' . $method . $path);
            Log::info(__METHOD__ . ' Request Parameters: ' . print_r($request, true));
            $response = $client->request(
                $method,
                $path,
                [
                    'json' => $request,
                    'http_errors' => false
                ]
            );

            $responseBody = (string) $response->getBody();
            $statusCode = $response->getStatusCode();
            Log::info(__METHOD__ . ' Response[status code]: ' . $statusCode);
            Log::info(__METHOD__ . ' Response[body]: ' . $responseBody);
            $result = array(
                'body' => $responseBody,
                'statusCode' => $statusCode,
            );
            return $result;
    }
}