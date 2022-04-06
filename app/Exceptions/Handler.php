<?php

namespace App\Exceptions;

use App\Lib\ApiCode;
use App\Lib\CustomResponseBuilder;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Routing\Exceptions\UrlGenerationException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException as ValEx;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (ValEx $e, $request) {
            if ($request->expectsJson()) {
                return $this->respondWithValidationError($e);
            }
        });

        $this->renderable(function (UrlGenerationException $e, $request) {
            if ($request->expectsJson()) {
                return $this->respondWithBadRequest($e);
            }
        });

        $this->renderable(function (QueryException $e, $request) {
            if ($request->expectsJson()) {
                return respondWithError(ApiCode::SERVER_ERROR);
            }
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return $this->respondWithNotFoundError($e);
            }
        });

        $this->renderable(function (MethodNotAllowedException $e, $request) {
            if ($request->expectsJson()) {
                return $this->respondWithNotFoundError($e);
            }
        });

        // $this->renderable(function (\Exception $e, $request) {
        //     if ($request->expectsJson()) {
        //         return respondWithError(ApiCode::SERVER_ERROR);
        //     }
        // });

        $this->reportable(function (Throwable $e) {
            // if ($request->expectsJson()) {
            //     return  CustomResponseBuilder::asError(ApiCode::SERVER_ERROR)
            //         ->withHttpCode(200)
            //         ->build();
            // }
        });
    }

    public function respondWithValidationError($exception): \Illuminate\Http\JsonResponse
    {
        $errorData = [];
        foreach ($exception->errors() as $key => $message) {
            $errorData[$key] = $message[0];
        }

        return CustomResponseBuilder::asError(ApiCode::VALIDATION_ERROR)
            ->withData($errorData)
            ->withHttpCode(200)
            ->build();
    }

    protected function respondWithBadRequest($exception): \Illuminate\Http\JsonResponse
    {
        return CustomResponseBuilder::asError(ApiCode::BAD_REQUEST)
            ->withHttpCode(200)
            ->withData($exception->getMessage())
            ->build();
    }

    protected function respondWithNotFoundError($exception): \Illuminate\Http\JsonResponse
    {
        return CustomResponseBuilder::asError(ApiCode::NOT_FOUND)
            ->withHttpCode(200)
            ->build();
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return CustomResponseBuilder::asError(ApiCode::UNAUTHENTICATED)
                        ->withHttpCode(200)
                        ->build();
        // $request->headers->set('Accept', 'application/json');
        // return $this->shouldReturnJson($request, $exception)
        //             ? CustomResponseBuilder::asError(ApiCode::UNAUTHENTICATED)
        //                 ->withHttpCode(200)
        //                 ->build()
        //             : redirect()->guest($exception->redirectTo() ?? route('login'));
    }
}
