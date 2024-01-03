<?php

namespace App\Custom;

use Illuminate\Support\Facades\Log;

class ResponseManager
{
    public static function setSuccessResponse($HttpCode = 200, $message = '', $data = '') {
        if(empty($data)) return response([
            "status"    => true,
            "message"   => $message,
        ],$HttpCode);

        return response([
            "status"    => true,
            "message"   => $message,
            "data"      => $data,
        ],$HttpCode);

    }

    public static function setCrudErrors($CodHttp = 400, $message = '', $data = ''){
        return response([
            "status"    => true,
            "message"   => $message,
            "data"      => $data,
        ],$CodHttp);
    }

    public static function setErrorServerResponse($error = '', $location = '', $keyError = '', $viewError = false) {

        self::createLog($error,$location,$keyError);

        return response([
            "status"    => false,
            "message"   =>"Internal_error!!!",
            "error"     => ($viewError ? $error->getMessage(): ''),
        ],500);
    }

    public static function createLog($error = '', $location = '', $keyError = '') {
        $currentDate    = date('Y-m-d-H-i-s');
        $completePath   = "logs/".$currentDate."_".$keyError."_".$location.".log";

        Log::build([
            'driver' => 'single',
            'path' => storage_path($completePath),
        ])->info($error);
    }
}
