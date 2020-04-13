<?php

namespace Helldar\ApiResponse\Support;

use Exception as BaseException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response as LaravelResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Throwable;

final class Exception
{
    public static function isError($value = null): bool
    {
        return Instance::of($value, [BaseException::class, Throwable::class]);
    }

    public static function isErrorCode(int $code = 0): bool
    {
        return $code >= 400;
    }

    /**
     * @param  \Exception|\Throwable  $value
     * @param  int  $status_code
     *
     * @return int
     */
    public static function getCode($value, int $status_code = 400): int
    {
        if (static::isErrorCode($status_code)) {
            return $status_code;
        }

        if (Instance::of($value, ValidationException::class)) {
            return static::correctStatusCode(
                $value->status ?? Instance::call($value, 'getCode') ?: 0
            );
        }

        return static::correctStatusCode(
            Instance::callsWhenNotEmpty($value, ['getStatusCode', 'getCode']) ?: 0
        );
    }

    public static function getType(Throwable $class): string
    {
        return Instance::basename($class);
    }

    public static function getData($exception)
    {
        if ($exception instanceof SymfonyResponse || $exception instanceof LaravelResponse) {
            return method_exists($exception, 'getOriginalContent')
                ? $exception->getOriginalContent()
                : $exception->getContent() ?? $exception->getMessage();
        }

        if ($exception instanceof Responsable || $exception instanceof HttpResponseException) {
            return $exception->getResponse();
        }

        if ($exception instanceof ValidationException) {
            return $exception->errors();
        }

        return $exception->getMessage();
    }

    protected static function correctStatusCode(int $code): int
    {
        return static::isErrorCode($code) ? $code : 400;
    }
}
