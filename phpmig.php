<?php

use Codeages\Biz\Framework\Dao\MigrationBootstrap;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\HttpFoundation\Request;

$input = new ArgvInput();
$env = $input->getParameterOption(array('--env', '-e'), getenv('SYMFONY_ENV') ?: 'dev');



$biz = require_once __DIR__ . '/biz.php';
$biz['migration.directories'][] = __DIR__ . '/migrations';

$migration = new MigrationBootstrap($biz['db'], $biz['migration.directories']);

$container = $migration->boot();

return $container;
