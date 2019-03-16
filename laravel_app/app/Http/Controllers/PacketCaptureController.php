<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\WebAPI\WebAPI;
use Log;

class PacketCaptureController extends Controller
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
     * Show packet capture index page
     * 
     * @param   Request     $request
     * @return  \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $apiPath = config('api.ver') . config('api.capture_data');
        $requestParams = array(
            'user_id' => $request->session()->get('user_id'),
        );

        // call API GET /packet_capture.json
        /*$webApi = new WebAPI;
        $response = $webApi->callAPI($requestParams, 'GET', $apiPath);
        Log::debug(__METHOD__ . ' response = ' . print_r($response, true));

        // set response data in session
        $array = json_decode($response['body'], true);
        Log::debug(__METHOD__ . ' array = ' . print_r($array, true));*/

        //if ($response['statusCode'] === 200) {
            return view('packet_capture.index');
        /*} else {
            $errMsg = config('messages.error.dashboard.fail');
            $request->session()->flash('message', $errMsg);
            return view('dashboards.index');
        }*/
    }

    /**
     * Show a new packet capture index page
     * 
     * @param   Request     $request
     * @return  \Illuminate\Contracts\Support\Renderable
     */
    public function indexNew(Request $request)
    {
        return view('packet_capture.index_new');
    }

    /**
     * Create new capture data
     * 
     * @param   Request    $request
     * @return  \Illuminate\Contracts\Support\Renderable
     */
    public function store(Request $request)
    {
        $apiPath = config('api.ver') . config('api.capture_data');
        $requestParams = array(
            'user_id' => $request->session()->get('user_id'),
            'data_name' => $request->input('data_name'),
            'data_summary' => $request->input('data_summary'),
        );

        // call API POST /capture_data.json
        $webApi = new WebAPI;
        $response = $webApi->callAPI($requestParams, 'POST', $apiPath);
        Log::debug(__METHOD__ . ' response = ' . print_r($response, true));

        // set response data in session
        $array = json_decode($response['body'], true);
        Log::debug(__METHOD__ . ' array = ' . print_r($array, true));

        if ($response['statusCode'] === 200) {
            return redirect()->to('/dashboard');
        } else {
            $errMsg = config('messages.error.dashboard.fail');
            $request->session()->flash('message', $errMsg);
            return redirect()->back();
        }
    }
}