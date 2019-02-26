<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * encode request object to json
     * @param       Request $request
     * @return      json
     */
    private function encodeToJson(Request $request)
    {
        $arrayRequest = $request->all();
        Log::debug(__METHOD__ . 'arrayRequest = ' . $arrayRequest);
        $jsonRequest = json_encode($arrayRequest);
        Log::debug(__METHOD__ . 'jsonRequest = ' . $jsonRequest);
        return $jsonRequest;
    }
}
