<?php

namespace Taka512\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Taka512\ContainerFactory;
use Taka512\LoggerFactory;

abstract class BaseCommand extends Command
{
    protected $container;

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->container = ContainerFactory::getContainer();
        LoggerFactory::initLoggerByBatch(
            $this->getName(),
            $this->container['settings']['logger']['path'],
            $this->container['settings']['logger']['level']
        );
        try {
            $this->process($input);
        } catch (\Exception $e) {
            $this->get('logger')->error(sprintf('message:%s file:%s line:%s', $e->getMessage(), $e->getFile(), $e->getLine()));
        }
    }

    abstract public function process(InputInterface $input);

    protected function get(string $name)
    {
        return $this->container[$name];
    }
}
