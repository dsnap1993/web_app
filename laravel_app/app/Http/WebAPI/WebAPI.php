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
            Log::info(__METHOD__ . ' [Call API]' . $method . $path);
            Log::info(__METHOD__ . ' Request Parameters: ' . $request);
            $response = $client->request(
                $method,
                $path,
                ['json' => $request]
            );

            $responseBody = (string) $response->getBody();
            $statusCode = $response->getStatusCode();
            Log::info(__METHOD__ . ' Response Body: ' . $responseBody);
            Log::info(__METHOD__ . ' statusCode: ' . $statusCode);
            $result = array(
                'body' => $responseBody,
                'statusCode' => $statusCode,
            );
            return $result;
        } catch(RequestException $e) {
            Log::error(__METHOD__ . ' Exception[Request]: ' . Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                Log::error(__METHOD__ . ' Exception[Response]: ' . Psr7\str($e->getResponse()));
                $errMsg = Config::get('messages.error.login.other');
                return redirect()->action('IndexAction')->withErrors($errmsg);
            }
        }
    }
}