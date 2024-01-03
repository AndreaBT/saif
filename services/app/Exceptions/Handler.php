<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->is('api/*')) {
                $currentDate = date('Y-m-d-H-i-s');
                $completePath = "logs/".$currentDate."_GeneralServerError.log";

                Log::build([
                    'driver' => 'single',
                    'path' => storage_path($completePath),
                ])->info($e);

                return response([
                    "status"    => false,
                    "message"   =>"Internal_error!!!",
                    "error"     => $e->getMessage(),
                ],500);
            }
        });


    }
}
