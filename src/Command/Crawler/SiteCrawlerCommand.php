<?php

namespace Taka512\Command\Crawler;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Taka512\Command\BaseCommand;

class SiteCrawlerCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('crawler:site')
            ->setDescription('crawler:site')
            ->addOption(
                'config',
                null,
                InputOption::VALUE_REQUIRED,
                'configure file'
            )->addOption(
                'dry-run',
                null,
                InputOption::VALUE_NONE,
                'dry run option'
            );
    }

    public function process(InputInterface $input)
    {
        $this->get('logger')->info($input->getOption('config'));
        $this->get('logger')->error($input->getOption('dry-run'));
        echo "DONE\n";
    }
}
