<?php

if (! function_exists('respond')) {
    function respond(int $api_code = 200, $data = null, $message = null): \Illuminate\Http\JsonResponse
    {
        return \App\Lib\CustomResponseBuilder::asSuccess($api_code)->withData($data)->withMessage($message)->build();
    }
}

if (! function_exists('respondWithMessage')) {
    function respondWithMessage(string $message, $as = 'success'): \Illuminate\Http\JsonResponse
    {
        if ($as === 'error') {
            $builder = new \App\Lib\CustomResponseBuilder(false, 200);
            return $builder->withMessage($message)->withHttpCode(200)->build();
        }
        return \App\Lib\CustomResponseBuilder::asSuccess()->withMessage($message)->build();
    }
}

if (! function_exists('respondWithError')) {
    function respondWithError($api_code, $data = null, $http_code = 200): \Illuminate\Http\JsonResponse
    {
        return \App\Lib\CustomResponseBuilder::asError($api_code)->withHttpCode($http_code)->withData($data)->build();
    }
}
