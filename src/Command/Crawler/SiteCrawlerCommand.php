<?php

namespace Taka512\Command\Crawler;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Taka512\Command\BaseCommand;
use Taka512\Http\ClientFactory;

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
        $this->get(LoggerInterface::class)->info($input->getOption('config'));
        $client = ClientFactory::createChrome();
        $crawler = $client->request('GET', 'https://www.google.co.jp/search?q=test');
        $nodeValues = $crawler->filterXPath('//div[contains(@class, "rc")]')->each(function ($node, $i) {
            return $node->text();
        });
        foreach ($nodeValues as $v) {
            $this->get(LoggerInterface::class)->info($v);
        }

        return Command::SUCCESS;
    }
}
