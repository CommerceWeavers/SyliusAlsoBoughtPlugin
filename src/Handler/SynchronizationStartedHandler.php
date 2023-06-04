<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Handler;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\ProductSynchronization;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Event\SynchronizationStarted;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class SynchronizationStartedHandler
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(SynchronizationStarted $event): void
    {
        $synchronizationStarted = new ProductSynchronization($event->id(), $event->date());

        $this->entityManager->persist($synchronizationStarted);
    }
}
