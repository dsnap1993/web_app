<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\WebAPI\WebAPI;
use Log;

class DashboardController extends Controller
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
     * Show dashboard index page
     * 
     * @param   DashboardRequest     $request
     * @return  \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $apiPath = config('api.ver') . config('api.capture_data');
        $requestParams = array(
            'user_id' => $request->session()->get('user_id'),
        );

        // call API GET /capture_data.json
        $webApi = new WebAPI;
        $response = $webApi->callGetAPI($requestParams, $apiPath);
        $array = json_decode($response['body'], true);
        Log::debug(__METHOD__ . ' array = ' . print_r($array, true));

        if ($response['statusCode'] === 200) {
            return view('dashboards.index', compact('array'));
        } else {
            $errMsg = config('messages.error.dashboard.fail');
            $request->session()->flash('message', $errMsg);
            return redirect()->back();
        }
    }

    /**
     * Update capture data on dashboard page
     * 
     * @param   Request    $request
     * @return  \Illuminate\Contracts\Support\Renderable
     */
    public function update(Request $request)
    {
        $apiPath = config('api.ver') . config('api.capture_data');
        $requestParams = array(
            'data_id' => (int)$request->input('data_id'),
            'data_name' => $request->input('data_name'),
            'data_summary' => $request->input('data_summary'),
            'file_name' => $request->input('file_name'),
        );

        // call API PUT /capture_data.json
        $webApi = new WebAPI;
        $response = $webApi->callPutAPI($requestParams, $apiPath);
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

    /**
     * Delete capture data on dashboard page
     * 
     * @param   Request    $request
     * @return  \Illuminate\Contracts\Support\Renderable
     */
    public function delete(Request $request)
    {
        $apiPath = config('api.ver') . config('api.capture_data');
        $requestParams = array(
            'data_id' => (int)$request->input('data_id'),
        );

        // call API DELETE /capture_data
        $webApi = new WebAPI;
        $response = $webApi->callDeleteAPI($requestParams, $apiPath);
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
