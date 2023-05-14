<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Handler;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Command\SynchronizeFrequentlyBoughtTogetherProducts;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Event\SynchronizationEnded;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Event\SynchronizationStarted;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Provider\LastSynchronizationDateProviderInterface;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Synchronizer\FrequentlyBoughtTogetherProductsSynchronizerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;
use Symfony\Component\Uid\Uuid;

final class SynchronizeFrequentlyBoughtTogetherProductsHandler implements MessageHandlerInterface
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
        $synchronizationResult = $this->frequentlyBoughtTogetherProductsSynchronizer->synchronize($lastSynchronizationDate);

        $event = new SynchronizationEnded(
            $synchronizationId,
            new \DateTimeImmutable(),
            $synchronizationResult->numberOfOrders(),
            $synchronizationResult->affectedProducts(),
        );
        $this->eventBus->dispatch($event, [new DispatchAfterCurrentBusStamp()]);
    }
}
