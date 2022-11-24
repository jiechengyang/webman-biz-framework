<?php

namespace support\bootstrap\biz;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Codeages\Biz\Framework\Dao\DaoProxy;

class DaoProvider implements ServiceProviderInterface
{
    public function register(Container $biz)
    {
        $biz['autoload.object_maker.dao'] = function ($biz) {
            return function ($namespace, $name) use ($biz) {
                $class = "{$namespace}\\Dao\\Impl\\{$name}Impl";

                if ('Biz\\' === substr($namespace, 0, strlen('Biz\\'))) {
                    $ctNamespace = "{$this->getNamespace()}\\{$namespace}";
                    $ctClass = "{$ctNamespace}\\Dao\\Impl\\{$name}Impl";
                    if (class_exists($ctClass)) {
                        $class = $ctClass;
                    }
                }

                return new DaoProxy($biz, new $class($biz), $biz['dao.metadata_reader'], $biz['dao.serializer']);
            };
        };
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
