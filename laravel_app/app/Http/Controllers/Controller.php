<?php

namespace App\Http\Controllers;

//use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
//use Illuminate\Foundation\Validation\ValidatesRequests;
//use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Http\Requests;
use Log;

class Controller extends BaseController
{
    //use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * encode request object to json
     * @param       Request $request
     * @return      json
     */
    protected function encodeToJson(Request $request)
    {
        $arrayRequest = $request->all();
        unset($arrayRequest['_token']);
        $jsonRequest = json_encode($arrayRequest);
        Log::debug(__METHOD__ . ' jsonRequest = ' . $jsonRequest);
        return $jsonRequest;
    }
}
