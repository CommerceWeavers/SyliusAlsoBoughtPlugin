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
        $thirdOrder = new Order();

        $synchronizer = new FrequentlyBoughtTogetherProductsSynchronizer(
            $placedOrdersProvider->reveal(),
            $productsMapper->reveal(),
            $boughtTogetherProductsInfoSaver->reveal(),
            2,
        );

        $placedOrdersProvider
            ->getSince(new \DateTimeImmutable('2020-01-01'), 2, 0)
            ->willReturn([$firstOrder, $secondOrder])
        ;

        $placedOrdersProvider
            ->getSince(new \DateTimeImmutable('2020-01-01'), 2, 2)
            ->willReturn([$thirdOrder])
        ;

        $placedOrdersProvider
            ->getSince(new \DateTimeImmutable('2020-01-01'), 2, 4)
            ->willReturn([])
        ;

        $productsMapper->map($firstOrder)->willReturn(['P10273' => ['P12182', 'P12183'], 'P12182' => ['P10273', 'P12183'], 'P12183' => ['P10273', 'P12182']]);
        $productsMapper->map($secondOrder)->willReturn(['P12182' => ['P10273'], 'P10273' => ['P12182']]);
        $productsMapper->map($thirdOrder)->willReturn(['P13757' => ['P10273']]);

        $boughtTogetherProductsInfoSaver->save('P10273', ['P12182', 'P12183', 'P12182'])->shouldBeCalled();
        $boughtTogetherProductsInfoSaver->save('P12182', ['P10273', 'P12183', 'P10273'])->shouldBeCalled();
        $boughtTogetherProductsInfoSaver->save('P12183', ['P10273', 'P12182'])->shouldBeCalled();
        $boughtTogetherProductsInfoSaver->save('P13757', ['P10273'])->shouldBeCalled();

        self::assertEquals(
            new SynchronizationResult(3, ['P10273', 'P12182', 'P12183', 'P13757']),
            $synchronizer->synchronize(new \DateTimeImmutable('2020-01-01')),
        );
    }
}
