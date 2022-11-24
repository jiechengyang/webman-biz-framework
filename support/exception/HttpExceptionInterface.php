<?php


namespace support\exception;


/**
 * Interface for HTTP error exceptions.
 * @package app\common\exceptions
 * @author Kris Wallsmith <kris@symfony.com>
 */
interface HttpExceptionInterface
{
    /**
     * Returns the status code.
     *
     * @return int An HTTP response status code
     */
    public function getStatusCode();

    /**
     * Returns response headers.
     *
     * @return array Response headers
     */
    public function getHeaders();
}