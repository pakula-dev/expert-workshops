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
        $status = 500;
        $response = ["errorCode" => 9999];
        switch (get_class($exception)) {
            case "Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException":
            case "Symfony\Component\HttpKernel\Exception\NotFoundHttpException":
            case "Symfony\Component\HttpKernel\Exception\HttpException":
                $status = $exception->getStatusCode();
                $response['errorCode'] = $exception->getStatusCode();
                break;
            case "Illuminate\Database\Eloquent\ModelNotFoundException":
                $status = 404;
                $response['errorCode'] = 404;
                break;
            case "Illuminate\Validation\ValidationException":
                $status = $exception->status;
                $errors = $exception->errors();
                $errorsKeys = array_keys($errors);
                $response['errorCode'] = $errors[$errorsKeys[0]][0];
                break;
            case "Illuminate\Auth\AuthenticationException":
                if ($exception->getMessage() == 'Unauthenticated.') {
                    $response['errorCode'] = 401;
                    $status = 401;
                }
                break;
            default:
//              dd($exception);
                $response['errorCode'] = $exception->getCode() ?? 9999;
        }

        if (env('APP_ENV') != 'production') {
            $response['url'] = $request->getRequestUri();
            $response['file'] = $exception->getFile();
            $response['line'] = $exception->getLine();
            $response['message'] = $exception->getMessage();
            $response['trace'] = $exception->getTrace();
        }

        $response['errorCode'] = (int)$response['errorCode'];

        return response()->json($response, $status);
    }
}
