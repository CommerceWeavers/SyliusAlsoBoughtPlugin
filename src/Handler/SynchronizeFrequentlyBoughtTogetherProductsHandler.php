<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Handler;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Command\SynchronizeFrequentlyBoughtTogetherProducts;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Event\SynchronizationEnded;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Event\SynchronizationStarted;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Provider\LastSynchronizationDateProviderInterface;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Synchronizer\FrequentlyBoughtTogetherProductsSynchronizerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
final class SynchronizeFrequentlyBoughtTogetherProductsHandler
{
    public function __construct(
        private LastSynchronizationDateProviderInterface $lastSynchronizationDateProvider,
        private FrequentlyBoughtTogetherProductsSynchronizerInterface $frequentlyBoughtTogetherProductsSynchronizer,
        private MessageBusInterface $eventBus,
    ) {
    }

    public function __invoke(SynchronizeFrequentlyBoughtTogetherProducts $command): void
    {
        $synchronizationId = Uuid::v4();

        $this->eventBus->dispatch(new SynchronizationStarted($synchronizationId, new \DateTimeImmutable()));

        $lastSynchronizationDate = $this->lastSynchronizationDateProvider->provide();

        $synchronizationResult = $this->frequentlyBoughtTogetherProductsSynchronizer->synchronize(
            $lastSynchronizationDate ?? (new \DateTimeImmutable())->setTimestamp(0)
        );

        $event = new SynchronizationEnded(
            $synchronizationId,
            new \DateTimeImmutable(),
            $synchronizationResult->numberOfOrders(),
            $synchronizationResult->affectedProducts(),
        );
        $this->eventBus->dispatch($event, [new DispatchAfterCurrentBusStamp()]);
    }
}
