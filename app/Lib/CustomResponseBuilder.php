<?php

namespace App\Lib;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Lang;

/**
 * Build Consistent Api Responses
 */
class CustomResponseBuilder
{
    protected array $allowedTypes = ['array', 'string', "NULL", 'object', 'integer', 'boolean'];

    protected $data;
    protected int $http_code;
    protected int $api_code;
    protected $message;

    /**
     * @param bool $success
     * @param int $api_code
     */
    public function __construct(bool $success, int $api_code)
    {
        $this->success = $success;
        $this->api_code = $api_code;
    }

    public static function asSuccess(int $api_code = 200) : self
    {
        return new static(true, $api_code);
    }

    public static function custom(bool $status = true, int $api_code = 200) : self
    {
        return new static($status, $api_code);
    }

    public static function asError($api_code = 400) : self
    {
        return new static(false, $api_code);
    }

    public function withData($data) : self
    {
        $type = gettype($data);
        if (! in_array($type, $this->allowedTypes, true)) {
            throw new \RuntimeException(sprintf('"%s" must be one of allowed types: "%s" but "%s" found.', 'data', $this->allowedTypes, $type));
        }
        $this->data = $data;
        return $this;
    }

    public function withHttpCode(int $http_code) : self
    {
        $type = gettype($http_code);
        $allowedTypes = ['integer'];
        if (! in_array($type, $allowedTypes, true)) {
            throw new \RuntimeException(sprintf('"%s" must be "%s" but "%s" found.', 'message', implode(', ', $allowedTypes), $type));
        }
        $this->http_code = $http_code;
        return $this;
    }

    public function withMessage($message = null) : self
    {
        $type = gettype($message);
        $allowedTypes = ['string', "NULL"];
        if (! in_array($type, $allowedTypes, true)) {
            throw new \RuntimeException(sprintf('"%s" must be one of allowed types: "%s" but "%s" found.', 'message', implode(', ', $allowedTypes), $type));
        }
        $this->message = $message;
        return $this;
    }

    public function build(): \Illuminate\Http\JsonResponse
    {
        $api_code = $this->api_code;
        $type = gettype($api_code);
        $allowedTypes = ['integer'];
        if (! in_array($type, $allowedTypes, true)) {
            throw new \RuntimeException(sprintf('"%s" must be "%s" but "%s" found.', 'message', implode(', ', $allowedTypes), $type));
        }

        $msg_or_api_code = $this->message ?? $api_code;

        if ($this->success) {
            $http_code = $this->http_code ?? 200;
            if (! in_array($http_code, range(200, 299), true)) {
                throw new \RuntimeException(sprintf('"%s" is not OK http response', $http_code));
            }
            $result = $this->make($this->success, $api_code, $msg_or_api_code, $this->data, $http_code);
        } else {
            $http_code = $this->http_code ?? 400;

            // if (!in_array($http_code, range(400, 599), true)) {
            //  throw new \RuntimeException(sprintf("%s is not Bad http response", $http_code));
            // }
            $result = $this->make($this->success, $api_code, $msg_or_api_code, $this->data, $http_code);
        }
        return $result;
    }

    public function make(bool $success, int $api_code, $msg_or_api_code, $data = null, int $http_code = null) : \Illuminate\Http\JsonResponse
    {
        $http_code = $http_code ?? ($success ? 200 : 400);
        $range = range(100, 1024);
        if (! in_array($api_code, $range, true)) {
            throw new \RuntimeException(sprintf('Value of "%s" (%d) is out of bounds. Must be between %d-%d inclusive.', 'api_code', $range, $range[0], last($range)));
        }

        return Response::json($this->buildResponse($success, $api_code, $msg_or_api_code, $this->data), $http_code);
    }

    public function buildResponse(bool $success, int $api_code, $msg_or_api_code, $data = null): array
    {
        if (\is_int($msg_or_api_code)) {
            $message = $this->getMessageForApiCode($msg_or_api_code);
        } else {
            $message = $msg_or_api_code;
        }
        return [
            'success' => $success,
            'code'    => $api_code,
//            RB::KEY_LOCALE  => \App::getLocale(),
            'message' => $message,
            'data'    => (object)$data,
        ];
    }

    public function getMessageForApiCode($api_code)
    {
        $map = config('custom_api.map');
        return Lang::get($map[$api_code]);
    }
}
