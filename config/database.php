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

return [
    'default' => 'mysql',

    'connections' => [
        'mysql' => [
            'driver' => 'pdo_mysql',//mysql
            'host' => envHelper('DB_HOST', '127.0.0.1'),
            'port' => envHelper('DB_PORT', '3306'),
            'database' => envHelper('DB_DATABASE', 'flow_framework'),
            'username' => envHelper('DB_USERNAME', 'root'),
            'password' => envHelper('DB_PASSWORD', 'root'),
            'unix_socket' => envHelper('DB_SOCKET', ''),
            'charset' => 'utf8',
            'collation' => 'utf8_general_ci',
            'prefix' => 'smp_',
            'strict' => true,
            'engine' => null,
        ],

        'sqlite' => [
            'driver' => 'sqlite',
            'database' => envHelper('DB_DATABASE', ''),
            'prefix' => '',
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'host' => envHelper('DB_HOST', '127.0.0.1'),
            'port' => envHelper('DB_PORT', '5432'),
            'database' => envHelper('DB_DATABASE', 'forge'),
            'username' => envHelper('DB_USERNAME', 'forge'),
            'password' => envHelper('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'host' => envHelper('DB_HOST', 'localhost'),
            'port' => envHelper('DB_PORT', '1433'),
            'database' => envHelper('DB_DATABASE', 'forge'),
            'username' => envHelper('DB_USERNAME', 'forge'),
            'password' => envHelper('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
        ],
    ],
];
