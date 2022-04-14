<?php


namespace support\utils;


use support\exception\HttpExceptionInterface;
use Webman\Http\Response;

class ExceptionUtil
{
    public static function getErrorAndHttpCodeFromException($exception)
    {
        $error = [];
        if (self::checkIsBusinessException($exception)) {
            $error['message'] = $exception->getMessage();
            $error['code'] = $exception->getCode();
            $httpCode = $exception->getStatusCode();
        } else {
            $error['message'] = 'Internal server error';
            $error['code'] = $exception->getCode() ?: -1;
            $httpCode = 500;
        }

        return [$httpCode, $error];
    }

    private static function checkIsBusinessException($exception)
    {
        if ($exception instanceof HttpExceptionInterface) {
            return true;
        }

        return false;
    }
}