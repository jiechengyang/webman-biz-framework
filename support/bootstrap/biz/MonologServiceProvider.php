<?php


namespace support\bootstrap\biz;


use Codeages\Biz\Framework\Context\Biz;
use Codeages\Biz\Framework\Context\BootableProviderInterface;
use Monolog\ErrorHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\GroupHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class MonologServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{
    public function boot(Biz $app)
    {
        if (isset($app['monolog.use_error_handler'])) {
            ErrorHandler::register($app['monolog']);
        }
    }

    public function register(Container $app)
    {
        $app['logger'] = function () use ($app) {
            return $app['monolog'];
        };

        $app['monolog.logger.class'] = 'Monolog\Logger';

        $app['monolog'] = function ($app) {
            $log = new $app['monolog.logger.class']($app['monolog.name']);

            $handler = new GroupHandler($app['monolog.handlers']);
            if (isset($app['monolog.not_found_activation_strategy'])) {
                $handler = new FingersCrossedHandler($handler, $app['monolog.not_found_activation_strategy']);
            }

            $log->pushHandler($handler);

            if ($app['debug'] && isset($app['monolog.handler.debug'])) {
                $log->pushHandler($app['monolog.handler.debug']);
            }

            return $log;
        };

        $app['monolog.formatter'] = function () {
            return new LineFormatter();
        };

        $app['monolog.handler'] = $defaultHandler = function () use ($app) {
            $level = MonologServiceProvider::translateLevel($app['monolog.level']);

            $handler = new StreamHandler($app['monolog.logfile'], $level, $app['monolog.bubble'], $app['monolog.permission']);
            $handler->setFormatter($app['monolog.formatter']);

            return $handler;
        };

        $app['monolog.handlers'] = function () use ($app, $defaultHandler) {
            $handlers = array();

            if ($app['monolog.logfile'] || $defaultHandler !== $app->raw('monolog.handler')) {
                $handlers[] = $app['monolog.handler'];
            }

            return $handlers;
        };

        $app['monolog.level'] = function () {
            return Logger::DEBUG;
        };

        $app['monolog.name'] = 'app';
        $app['monolog.bubble'] = true;
        $app['monolog.permission'] = null;
        $app['monolog.exception.logger_filter'] = null;
        $app['monolog.logfile'] = null;
        $app['monolog.use_error_handler'] = function ($app) {
            return !$app['debug'];
        };
    }

    public static function translateLevel($name)
    {
        // level is already translated to logger constant, return as-is
        if (is_int($name)) {
            return $name;
        }

        $levels = Logger::getLevels();
        $upper = strtoupper($name);

        if (!isset($levels[$upper])) {
            throw new \InvalidArgumentException("Provided logging level '$name' does not exist. Must be a valid monolog logging level.");
        }

        return $levels[$upper];
    }
}