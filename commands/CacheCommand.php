<?php


namespace commands;


use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CacheCommand extends BaseCommand
{
    protected $useDb = false;

    protected function configure()
    {
        $this->setName('cache:flush')
            ->setDescription('清空系统缓存');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}