<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Cli;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Command\CreateBoughtTogetherProductAssociationType;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\BoughtTogetherProductsAwareInterface;
use Sylius\Component\Product\Repository\ProductAssociationTypeRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'sylius:also-bought:setup',
    description: 'Create "bought together" product association type.',
)]
final class CreateBoughtTogetherProductAssociationTypeCommand extends Command
{
    public function __construct(
        private MessageBusInterface $commandBus,
        private ProductAssociationTypeRepositoryInterface $productAssociationTypeRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Setup started.');

        $boughtTogetherAssociationType = $this->productAssociationTypeRepository
            ->findOneBy(['code' => BoughtTogetherProductsAwareInterface::BOUGHT_TOGETHER_ASSOCIATION_TYPE_CODE]);

        if (null === $boughtTogetherAssociationType) {
            $this->createBoughtTogetherProductAssociationType();

            $output->writeln('The "bought together" product association type created.');
        } else {
            $output->writeln('The "bought together" product association type already exists.');
        }

        $output->writeln('Setup finished successfully.');

        return Command::SUCCESS;
    }

    private function createBoughtTogetherProductAssociationType(): void
    {
        try {
            $this->commandBus->dispatch(new CreateBoughtTogetherProductAssociationType());
        } catch (HandlerFailedException $exception) {
            if (null !== $prevousException = $exception->getPrevious()) {
                throw $prevousException;
            }

            throw $exception;
        }
    }
}
