<?php


namespace support\exception;


class InvalidParamException extends \BadMethodCallException
{
    public function getName()
    {
        return 'Invalid Parameter';
    }
}