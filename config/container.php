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

//return new Webman\Container;
use Codeages\Biz\Framework\Context\Biz;

$container = new \Biz\Container();

$bizConfig = \config('app.biz_config');
if (!empty($bizConfig)) {
    $options = array_merge(\config('app.biz_config'), [
        'db.options' => [
            'dbname' => \config('database.connections.mysql.database'),
            'user' => \config('database.connections.mysql.username'),
            'password' => \config('database.connections.mysql.password'),
            'host' => \config('database.connections.mysql.host'),
            'port' => \config('database.connections.mysql.port'),
            'driver' => \config('database.connections.mysql.driver'),
            'charset' => \config('database.connections.mysql.charset'),
        ],
    ]);
    $container->set(Biz::class, ['values' => $options]);
}

return $container;