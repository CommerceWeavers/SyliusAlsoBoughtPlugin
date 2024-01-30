<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Unit\Synchronizer;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Mapper\BoughtTogetherProductsMapperInterface;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Model\SynchronizationResult;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Provider\PlacedOrdersProviderInterface;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Saver\BoughtTogetherProductsInfoSaverInterface;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Synchronizer\FrequentlyBoughtTogetherProductsSynchronizer;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Sylius\Component\Core\Model\Order;

final class FrequentlyBoughtTogetherProductsSynchronizerTest extends TestCase
{
    use ProphecyTrait;

    public function testProductsSynchronization(): void
    {
        $placedOrdersProvider = $this->prophesize(PlacedOrdersProviderInterface::class);
        $productsMapper = $this->prophesize(BoughtTogetherProductsMapperInterface::class);
        $boughtTogetherProductsInfoSaver = $this->prophesize(BoughtTogetherProductsInfoSaverInterface::class);

        $firstOrder = new Order();
        $secondOrder = new Order();

        $synchronizer = new FrequentlyBoughtTogetherProductsSynchronizer(
            $placedOrdersProvider->reveal(),
            $productsMapper->reveal(),
            $boughtTogetherProductsInfoSaver->reveal(),
        );

        $placedOrdersProvider
            ->getSince(new \DateTimeImmutable('2020-01-01'))
            ->willReturn([$firstOrder, $secondOrder])
        ;

        $productsMapper->map($firstOrder)->willReturn(['P10273' => ['P12182', 'P12183'], 'P12182' => ['P10273', 'P12183'], 'P12183' => ['P10273', 'P12182']]);
        $productsMapper->map($secondOrder)->willReturn(['P12182' => ['P10273'], 'P10273' => ['P12182']]);

        $boughtTogetherProductsInfoSaver->save('P10273', ['P12182', 'P12183', 'P12182'])->shouldBeCalled();
        $boughtTogetherProductsInfoSaver->save('P12182', ['P10273', 'P12183', 'P10273'])->shouldBeCalled();
        $boughtTogetherProductsInfoSaver->save('P12183', ['P10273', 'P12182'])->shouldBeCalled();

        self::assertEquals(
            new SynchronizationResult(2, ['P10273', 'P12182', 'P12183']),
            $synchronizer->synchronize(new \DateTimeImmutable('2020-01-01')),
        );
    }
}
