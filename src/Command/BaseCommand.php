<?php

namespace Taka512\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Taka512\ContainerFactory;

abstract class BaseCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ContainerFactory::initContainerOnBatch($this->getName());
        try {
            return $this->process($input);
        } catch (\Exception $e) {
            $this->get(LoggerInterface::class)->error(sprintf('message:%s file:%s line:%s', $e->getMessage(), $e->getFile(), $e->getLine()));

            return Command::FAILURE;
        }
    }

    abstract public function process(InputInterface $input);

    protected function get(string $name)
    {
        return ContainerFactory::getContainer()->get($name);
    }
}
