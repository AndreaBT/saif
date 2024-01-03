<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SystemController extends Controller
{
    public function creaEnlace(Request $request)
    {
        if(file_exists(public_path('storage'))) {
            return response([
                'status' =>  true,
                'message' => 'El directorio "public/Storage" ya existe',
            ],200);
        }

        app('files')->link(
          storage_path('app/public'), public_path('storage')
        );

        return response([
            'status' =>  true,
            'message' => 'El directorio "public/Storage" ha sido creado',
        ],201);

    }
}
