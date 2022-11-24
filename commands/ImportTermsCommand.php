<?php


namespace commands;


use Biz\Terms\Service\TermsService;
use support\utils\ExcelToolKit;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportTermsCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('terms:import')
            ->setDescription('导入条款')
            ->addArgument('flush', null, '是否清空存量数据');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $flush = $input->getArgument('flush');
        $file = config_path() . DIRECTORY_SEPARATOR . 'privateFiles/flow_framework.xlsx';
        $items = ExcelToolKit::import($file, ['catalog', 'name']);
        if (empty($items)) {
            $output->writeln("<error>导入失败：未有可导入的数据</error>");

            return false;
        }
        if ($flush) {
            $this->getTermsService()->truncate();
        }

        if ($this->getTermsService()->batchCreateTerms($items)) {
            $count = count($items);
            $output->writeln("<info>成功导入{$count}条数据</info>");

            return true;
        }

        $output->writeln("<info>导入失败了</info>");

        return false;
    }

    /**
     * @return TermsService
     * @throws \Webman\Exception\NotFoundException
     */
    protected function getTermsService()
    {
        return $this->getBiz()->service('Terms:TermsService');
    }
}