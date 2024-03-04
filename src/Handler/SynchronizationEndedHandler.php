<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Handler;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Doctrine\ORM\ProductSynchronizationRepositoryInterface;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\ProductSynchronization;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Event\SynchronizationEnded;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class SynchronizationEndedHandler
{
    public function __construct(private ProductSynchronizationRepositoryInterface $productSynchronizationRepository)
    {
    }

    public function __invoke(SynchronizationEnded $event): void
    {
        /** @var ProductSynchronization $productSynchronization */
        $productSynchronization = $this->productSynchronizationRepository->find($event->id());

        $productSynchronization->end($event->date(), $event->numberOfOrders(), $event->affectedProducts());

        $this->productSynchronizationRepository->add($productSynchronization);
    }
}
