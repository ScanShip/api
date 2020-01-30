<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $code = 200;
        $status = '';
        $message = '';

        if ($exception instanceof NotFoundHttpException) {
            $code = 404;
            $status = 'Not Found';
            $message = "This page doesn't exist";
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            $code = 405;
            $status = 'Method not allowed';
        }

        if ($exception instanceof AuthenticationException) {
            $code = 401;
            $status = 'Unauthentificated';
            $message = 'You need to send a token with the Authorization header';
        }

        if ($status != '') {
            return response()->json([
                'code' => $code,
                'status' => $status,
                'message' => $message,
            ], $code);
        }

        return parent::render($request, $exception);
    }
}
