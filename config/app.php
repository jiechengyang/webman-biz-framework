<?php
/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

use support\Request;

return [
    'debug' => true,
    'default_timezone' => 'Asia/Shanghai',
    'request_class' => Request::class,
    'public_path' => base_path() . DIRECTORY_SEPARATOR . 'public',
    'runtime_path' => base_path(false) . DIRECTORY_SEPARATOR . 'runtime',
    'controller_suffix' => '',
    'biz_config' => [
        'redis.options' => [
            'host' => envHelper('REDIS_HOST'),
        ],
        'debug' => envHelper('APP_DEBUG', true),
        'log_dir' => dirname(__DIR__) . '/runtime/biz/logs',
        'run_dir' => dirname(__DIR__) . '/runtime/biz/run',
        'cache_directory' => dirname(__DIR__) . '/runtime/biz/cache',
        'lock.flock.directory' => dirname(__DIR__) . '/runtime/biz/lock',
    ],
];
