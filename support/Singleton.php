<?php


namespace support;


trait Singleton
{
    private static $_instance = null;

    /**
     * @param array ...$args
     * @return null|static
     */
    public static  function getInstance(...$args)
    {
        if (is_null(self::$_instance)) {
            // TODO: new static and new self 区别在于存在继承时，new static决定在于当前调用，而new self 在于类本身
            self::$_instance = new static(...$args);
        }

        return self::$_instance;
    }
}