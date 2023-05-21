<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Cli;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Command\SynchronizeFrequentlyBoughtTogetherProducts;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

final class SynchronizeBoughtTogetherProductsCommand extends Command
{
    public function __construct(private MessageBusInterface $commandBus)
    {
        parent::__construct('app:bought-together-products:synchronize');    // TODO: is this the right name?
    }

    protected function configure(): void
    {
        $this->setDescription('Synchronize products that are usually bought together.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Synchronization of bought together products started.');

        try {
            $this->commandBus->dispatch(new SynchronizeFrequentlyBoughtTogetherProducts());
        } catch (HandlerFailedException $exception) {
            if (null !== $prevousException = $exception->getPrevious()) {
                throw $prevousException;
            }

            throw $exception;
        }

        $output->writeln('Synchronization of bought together products finished successfully.');

        return Command::SUCCESS;
    }
}
