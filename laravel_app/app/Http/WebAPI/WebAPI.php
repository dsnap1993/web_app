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
            'base_uri' => config('api.url'), 
            'timeout' => config('api.timeout')
        ]);
        $param = array();

        Log::info(__METHOD__ . ' [Call API]' . $method . $path);
        Log::info(__METHOD__ . ' Request Parameters: ' . print_r($request, true));
        if ($method != 'GET') {
            $param = [
                'json' => $request,
                'http_errors' => false
            ];
        } else {
            $param = [
                'query' => $request,
                'http_errors' => false
            ];
        }
        $response = $client->request($method, $path, $param);

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