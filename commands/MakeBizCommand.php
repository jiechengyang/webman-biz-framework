<?php


namespace commands;


use gii\GiiFactory;
use support\utils\ShellColorUtil;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeBizCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            // 命令的名称 （"php console_command" 后面的部分）
            ->setName('make:biz')
            // 运行 "php console_command list" 时的简短描述
            ->setDescription('生成biz')
            // 运行命令时使用 "--help" 选项时的完整命令描述
            // 配置一个参数
            ->addArgument('id', InputArgument::REQUIRED, '业务名称')
            ->addArgument('table', null, '数据表');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bizId = $input->getArgument('id');
        $table = $input->getArgument('table');
        $output->writeln(sprintf("正在生成Biz: %s", ShellColorUtil::showInfo($bizId)));
        try {
            $gii = GiiFactory::create('easy', $this->getBiz());
            $path = $gii->render([
                'tableName' => $table,
                'bizId' => $bizId,
                'prefix' => 'smp_'
            ]);
            $output->writeln(ShellColorUtil::showInfo("{$path}已创建"));
        } catch (\Exception $e) {
            $output->writeln(ShellColorUtil::showError("生成失败:{$e->getMessage()}"));
        }
    }
}