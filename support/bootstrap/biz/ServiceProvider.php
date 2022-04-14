<?php

namespace support\bootstrap\biz;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $biz)
    {
        $biz['autoload.object_maker.service'] = function ($biz) {
            return function ($namespace, $name) use ($biz) {
                return $this->loadService($biz, $namespace, $name);
            };
        };
    }

    protected function loadService($biz, $namespace, $name)
    {
        $class = "{$namespace}\\Service\\Impl\\{$name}Impl";

        if ('Biz\\' === substr($namespace, 0, strlen('Biz\\'))) {
            $ctNamespace = "{$this->getNamespace()}\\{$namespace}";
            $ctClass = "{$ctNamespace}\\Service\\Impl\\{$name}Impl";
            if (class_exists($ctClass)) {
                $class = $ctClass;
            }
        }

        return new $class($biz);
    }

    protected function loadResourceService($biz, $namespace, $name)
    {
        $class = "{$namespace}\\Resources\\Impl\\{$name}Impl";

        if ('Biz\\' === substr($namespace, 0, strlen('Biz\\'))) {
            $ctNamespace = "{$this->getNamespace()}\\{$namespace}";
            $ctClass = "{$ctNamespace}\\Resources\\Impl\\{$name}Impl";
            if (class_exists($ctClass)) {
                $class = $ctClass;
            }
        }

        return new $class($biz);
    }

    /**
     * Gets the Bundle namespace.
     *
     * @return string The Bundle namespace
     */
    public function getNamespace()
    {
        $class = get_class($this);

        return substr($class, 0, strrpos($class, '\\'));
    }
}
