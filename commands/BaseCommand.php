<?php


namespace commands;

use Biz\Container;
use Codeages\Biz\Framework\Context\Biz;
use Illuminate\Database\Capsule\Manager as Capsule;
use Monolog\Logger;
use support\bootstrap\biz\DaoProvider;
use support\bootstrap\biz\ExtensionsProvider;
use support\bootstrap\biz\MonologServiceProvider;
use support\bootstrap\biz\ServiceProvider;
use support\ServiceKernel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BaseCommand extends Command
{
    protected $useDb = false;

    protected static $biz = null;

    public function __construct(string $name = null)
    {
        parent::__construct($name);
        ServiceKernel::create('env', true);
        ServiceKernel::instance()->setBiz($this->getBiz());
    }

    protected function configure()
    {
        $this->setName('base')->setDescription('基类,不对外使用');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return 0;
    }

    /**
     * @return Biz
     * @throws \Webman\Exception\NotFoundException
     */
    protected function getBiz()
    {
        if (self::$biz !== null) {
            return self::$biz;
        }

        $options = [
            'debug' => \envHelper('APP_DEBUG', true),
            'log_dir' => dirname(__DIR__) . '/runtime/biz/logs',
            'run_dir' => dirname(__DIR__) . '/runtime/biz/run',
            'cache_directory' => dirname(__DIR__) . '/runtime/biz/cache',
            'lock.flock.directory' => dirname(__DIR__) . '/runtime/biz/lock',
            'db.options' => [
                'dbname' => \envHelper('DB_DATABASE') ?: 'flow_framework',
                'user' => \envHelper('DB_USERNAME') ?: 'root',
                'password' => \envHelper('DB_PASSWORD') ?: 'root',
                'host' => \envHelper('DB_HOST') ?: '127.0.0.1',
                'port' => \envHelper('DB_PORT') ?: 3306,
                'driver' => 'pdo_mysql',
                'charset' => 'utf8',
            ],
        ];

        $biz = new Biz($options);
        $biz->register(new \Codeages\Biz\Framework\Provider\DoctrineServiceProvider());
        $biz->register(new \Codeages\Biz\Framework\Provider\TargetlogServiceProvider());
        $biz->register(new MonologServiceProvider, [
            'monolog.logfile' => $biz['log_dir'] . '/' . date('Ym') . '/' . date('d') . '.log',
            'monolog.level' => $biz['debug'] ? Logger::DEBUG : Logger::INFO,
            'monolog.permission' => 0666
        ]);

        $biz->register(new DaoProvider());
        $biz->register(new ServiceProvider());
        $biz->register(new ExtensionsProvider());
        $biz->boot();

        self::$biz = $biz;

        return $biz;
    }
}