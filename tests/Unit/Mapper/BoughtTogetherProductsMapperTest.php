<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Unit\Mapper;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Mapper\BoughtTogetherProductsMapper;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Sylius\Component\Core\Model\Order;
use Sylius\Component\Core\Model\OrderItemInterface;
use Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Application\Entity\Product;

final class BoughtTogetherProductsMapperTest extends TestCase
{
    use ProphecyTrait;

    public function testRetrievingInformationAboutBoughtTogetherProducts(): void
    {
        $order = new Order();

        $firstOrderItem = $this->prophesize(OrderItemInterface::class);
        $firstProduct = new Product();
        $firstProduct->setCode('10273');
        $firstOrderItem->getProduct()->willReturn($firstProduct);
        $firstOrderItem->getTotal()->willReturn(1000);
        $firstOrderItem->setOrder($order)->shouldBeCalled();

        $secondOrderItem = $this->prophesize(OrderItemInterface::class);
        $secondProduct = new Product();
        $secondProduct->setCode('12182');
        $secondOrderItem->getProduct()->willReturn($secondProduct);
        $secondOrderItem->getTotal()->willReturn(1500);
        $secondOrderItem->setOrder($order)->shouldBeCalled();

        $thirdOrderItem = $this->prophesize(OrderItemInterface::class);
        $thirdProduct = new Product();
        $thirdProduct->setCode('12183');
        $thirdOrderItem->getProduct()->willReturn($thirdProduct);
        $thirdOrderItem->getTotal()->willReturn(2000);
        $thirdOrderItem->setOrder($order)->shouldBeCalled();

        $order->addItem($firstOrderItem->reveal());
        $order->addItem($secondOrderItem->reveal());
        $order->addItem($thirdOrderItem->reveal());

        self::assertSame(
            ['10273' => ['12182', '12183'], '12182' => ['10273', '12183'], '12183' => ['10273', '12182']],
            (new BoughtTogetherProductsMapper())->map($order),
        );
    }
}
