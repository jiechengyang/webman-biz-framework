<?php

namespace support\bootstrap\biz;

use Biz\Queue\Driver\RedisQueue;
use Codeages\Biz\Framework\Context\Biz;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use support\bootstrap\Redis;

class ExtensionsProvider implements ServiceProviderInterface
{
    public function register(Container $biz)
    {
        $biz['redis.api.cache'] = function ($biz) {
            return Redis::connection('apiCache');
        };
    }
}
