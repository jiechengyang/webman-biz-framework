<?php

use Codeages\Biz\Framework\Context\Biz;

$options = array(
    'db.options' => array(
        'dbname' => envHelper('DB_DATABASE') ?: 'flow_framework',
        'user' => envHelper('DB_USERNAME') ?: 'root',
        'password' => envHelper('DB_PASSWORD') ?: 'root',
        'host' => envHelper('DB_HOST') ?: '127.0.0.1',
        'port' => envHelper('DB_PORT') ?: 3306,
        'driver' => 'pdo_mysql',
        'charset' => 'utf8',
    ),
    'redis.options' => array(
        'host' => envHelper('REDIS_HOST'),
    ),
    'debug' => true,
    'log_dir' => __DIR__ . '/runtime/logs',
    'run_dir' => __DIR__ . '/runtime/run',
    'lock.flock.directory' => __DIR__ . '/runtime/run',
);

$biz = new Biz($options);
$biz->register(new \Codeages\Biz\Framework\Provider\DoctrineServiceProvider());
$biz->register(new \Codeages\Biz\Framework\Provider\TargetlogServiceProvider());
$biz->register(new \Codeages\Biz\Framework\Provider\MonologServiceProvider(), [
    'monolog.logfile' => $biz['log_dir'] . '/biz.log',
]);

$biz->boot();

return $biz;