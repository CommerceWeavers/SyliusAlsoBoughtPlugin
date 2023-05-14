<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Handler;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\ProductSynchronization;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Event\SynchronizationEnded;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class SynchronizationEndedHandler implements MessageHandlerInterface
{
    /**
     * @param RepositoryInterface<ProductSynchronization> $productSynchronizationRepository
     */
    public function __construct(private RepositoryInterface $productSynchronizationRepository)
    {
    }

    public function __invoke(SynchronizationEnded $event): void
    {
        /** @var ProductSynchronization $productSynchronization */
        $productSynchronization = $this->productSynchronizationRepository->find(
            // TODO: Check if RFC4122 is necessary
            $event->id()->toRfc4122()
        );

        $productSynchronization->end($event->date(), $event->numberOfOrders(), $event->affectedProducts());
    }
}
