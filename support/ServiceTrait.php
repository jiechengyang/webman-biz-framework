<?php


namespace support;


use AppBundle\Common\Exception\AbstractException;

trait ServiceTrait
{

    /**
     * @param $e
     * @throws \Exception
     */
    protected function createNewException($e)
    {
        if ($e instanceof \support\exception\AbstractException) {
            throw $e;
        }

        throw new \Exception($e);
    }
}