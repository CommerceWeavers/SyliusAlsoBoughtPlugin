<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Unit\Handler;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Command\SynchronizeFrequentlyBoughtTogetherProducts;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Event\SynchronizationEnded;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Event\SynchronizationStarted;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Handler\SynchronizeFrequentlyBoughtTogetherProductsHandler;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Model\SynchronizationResult;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Provider\LastSynchronizationDateProviderInterface;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Synchronizer\FrequentlyBoughtTogetherProductsSynchronizerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

final class SynchronizeFrequentlyBoughtTogetherProductsHandlerTest extends TestCase
{
    use ProphecyTrait;

    public function testHandlingFrequentlyBoughtTogetherProductsSynchronization(): void
    {
        $provider = $this->prophesize(LastSynchronizationDateProviderInterface::class);
        $synchronizer = $this->prophesize(FrequentlyBoughtTogetherProductsSynchronizerInterface::class);
        $eventBus = $this->prophesize(MessageBusInterface::class);

        $handler = new SynchronizeFrequentlyBoughtTogetherProductsHandler(
            $provider->reveal(),
            $synchronizer->reveal(),
            $eventBus->reveal(),
        );

        $eventBus
            ->dispatch(Argument::type(SynchronizationStarted::class))
            ->willReturn(new Envelope(Argument::type(SynchronizationStarted::class)))
            ->shouldBeCalled()
        ;

        $lastSynchronizationDate = new \DateTimeImmutable('2020-01-01');
        $provider->provide()->willReturn($lastSynchronizationDate);
        $synchronizer
            ->synchronize($lastSynchronizationDate)
            ->willReturn(new SynchronizationResult(5, ['10273', '12182', '23854', '15499', '28009']))
        ;

        $eventBus
            ->dispatch(
                Argument::type(SynchronizationEnded::class),
                Argument::containing(new DispatchAfterCurrentBusStamp())
            )
            ->willReturn(new Envelope(Argument::type(SynchronizationEnded::class)))
            ->shouldBeCalled()
        ;

        $handler(new SynchronizeFrequentlyBoughtTogetherProducts());
    }
}
