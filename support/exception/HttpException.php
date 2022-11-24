<?php


namespace support\exception;


use Throwable;

class HttpException extends \RuntimeException implements HttpExceptionInterface
{
    private $statusCode;
    private $headers;

    public function __construct($statusCode, $message = null, \Exception $previous = null, array $headers = [], $code = 0)
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode()
    {
        // TODO: Implement getStatusCode() method.
        return $this->statusCode;
    }

    public function getHeaders()
    {
        // TODO: Implement getHeaders() method.
        return $this->headers;
    }
}