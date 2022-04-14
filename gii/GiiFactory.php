<?php

namespace gii;

use Gii\template\BaseProcessor;
use InvalidArgumentException;

class GiiFactory
{
    /**
     * @param $type
     * @param $biz
     * @return BaseProcessor
     */
    public static function create($type, $biz)
    {
        $class = __NAMESPACE__ . "\\template\\${type}\\" . ucfirst($type) . "Processor";
        if (class_exists($class)) {
            return new $class($biz);
        }

        throw new InvalidArgumentException($type . ' biz模板不存在');
    }
}