<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Handler;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\ProductSynchronization;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Event\SynchronizationEnded;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class SynchronizationEndedHandler
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
        $productSynchronization = $this->productSynchronizationRepository->find($event->id());

        $productSynchronization->end($event->date(), $event->numberOfOrders(), $event->affectedProducts());
    }
}
