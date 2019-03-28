<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;

class FileController extends Controller
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
     * Download a file
     * 
     * @param   $file_name  file name
     * @return  \Illuminate\Contracts\Support\Renderable
     */
    public function download($file_name)
    {
        $path = "app/public/" . $file_name . ".pcap";
        $pathToFile = storage_path($path);
        return response()->download($pathToFile);
    }

    /**
     * Upload a file
     * 
     * @param   Request   $request
     * @param   $fileName   file name
     * @return  \Illuminate\Contracts\Support\Renderable
     */
    public function upload(Request $request, $fileName)
    {
        $fullFileName = $fileName . ".pcap";
        $request->file('file')->storeAs("public/", $fullFileName);
    }
}