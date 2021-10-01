<?php

namespace App\Exceptions;

// use Exception;
use InvalidArgumentException;
use BadMethodCallException;
use Illuminate\Database\QueryException;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use Illuminate\Validation\UnauthorizedException;
use Throwable;
use Illuminate\Support\Str;

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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        $url = $request->url();
        if(Str::contains($url, '/api')){
            switch($exception) {
                case $exception instanceof AuthorizationException:
                    return apiResponse(false,401,$exception->getMessage());
                case $exception instanceof UnauthorizedHttpException:
                    return apiResponse(false,401,'Unauthorized! Please Login');
                case $exception instanceof MethodNotAllowedHttpException:
                    return apiResponse(false,401,$exception->getMessage());
                case $exception instanceof NotFoundHttpException:
                    return apiResponse(false,401,'API not found');
                case $exception instanceof InvalidArgumentException:
                    return apiResponse(false,401,'API not found');
                case $exception instanceof BadMethodCallException:
                    return apiResponse(false,401,$exception->getMessage());
                case $exception instanceof FatalThrowableError:
                    return apiResponse(false,401,$exception->getMessage());
                case $exception instanceof QueryException:
                    return apiResponse(false,401,$exception->getMessage());
                break;
            }
        }
        return parent::render($request, $exception);
    }
}
