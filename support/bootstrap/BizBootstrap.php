<?php

namespace support\bootstrap;

use Monolog\Logger;
use Codeages\Biz\Framework\Context\Biz;
use support\bootstrap\biz\DoctrineServiceProvider;
use support\bootstrap\biz\ExtensionsProvider;
use support\bootstrap\biz\MonologServiceProvider;
use support\bootstrap\biz\ServiceProvider;
use support\bootstrap\biz\DaoProvider;
use support\bootstrap\Container;
use Webman\Bootstrap;

/**
 *
 *
 */
class BizBootstrap implements Bootstrap
{

    public static function start($worker)
    {
        /* @var $biz Biz */
        $biz = Container::get(Biz::class);
        $biz->register(new DoctrineServiceProvider());
        $biz->register(new \Codeages\Biz\Framework\Provider\TargetlogServiceProvider());
        $biz->register(new MonologServiceProvider(), [
            'monolog.logfile' => $biz['log_dir'] . '/' . date('Ym') . '/' . date('d') . '.log',
            'monolog.level' => $biz['debug'] ? Logger::DEBUG : Logger::INFO,
            'monolog.permission' => 0666
        ]);

        $biz->register(new ExtensionsProvider());
        $biz->register(new DaoProvider());
        $biz->register(new ServiceProvider());
        $biz->boot();
    }
}