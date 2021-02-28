<?php

namespace Taka512\Command;

use Psr\Log\LoggerInterface;
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
        ContainerFactory::initContainerOnBatch($this->getName());
        $this->container = ContainerFactory::getContainer();
        LoggerFactory::initLoggerByBatch(
            $this->getName(),
            $this->container->get('settings')['logger']['path'],
            $this->container->get('settings')['logger']['level']
        );
        try {
            return $this->process($input);
        } catch (\Exception $e) {
            $this->get(LoggerInterface::class)->error(sprintf('message:%s file:%s line:%s', $e->getMessage(), $e->getFile(), $e->getLine()));
        }
    }

    abstract public function process(InputInterface $input);

    protected function get(string $name)
    {
        return $this->container->get($name);
    }
}
