<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Sanctum\Exceptions\MissingAbilityException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        $code =  $e->getCode();
        // $msg  =  $e->getMessage();
        $msg  =  $e; //TODO: DELETE on production

        if ($e instanceof ValidationException) {
            $msg = $e->validator->errors()->first();
            $code = 400;
        } else if ($e instanceof NotFoundHttpException) {
            $code = 404;
            $msg = 'Route not found';
        } else if ($e instanceof AuthenticationException) {
            $code = 401;
            $msg = 'unauthenticated';
        } else if ($e instanceof MethodNotAllowedHttpException) {
            $code = 400;
            $msg = 'Bad request';
        } else if ($e instanceof ModelNotFoundException) {
            $code = 404;
            $msg = 'Not found';
        } else if ($e instanceof MissingAbilityException) {
            $code = 401;
            $msg = 'unauthenticated';
        } else if ($e instanceof ThrottleRequestsException) {
            $code = 400;
            $msg = 'Too many requests';
        } else if ($e instanceof InvalidRequestException) {
            $code = 400;
            $msg = $e->getMessage();
        } else if ($e instanceof \Exception) {
            // $code = 500;
            // $msg = 'somthing went wrong'; //TODO Uncomment
        }


        if (!$code || $code > 599 || $code <= 0 || gettype($code) !== "integer") {
            $code = 500;
            $msg = $e->getMessage();
        }

        return $this->fail($msg, $code);
    }
}
