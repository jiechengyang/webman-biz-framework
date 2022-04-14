<?php


namespace Biz;


use Webman\Exception\NotFoundException;

class Container extends \Webman\Container
{
    public function set($name, $args = [])
    {
        if (!class_exists($name)) {
            throw new NotFoundException("Class '$name' not found");
        }

        $reflection = new \ReflectionClass($name);

        $this->_instances[$name] = $reflection->newInstanceArgs($args);

        return $this->_instances[$name];
    }
}