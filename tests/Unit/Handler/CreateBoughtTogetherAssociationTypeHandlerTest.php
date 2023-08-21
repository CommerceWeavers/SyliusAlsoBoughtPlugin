<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Unit\Handler;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Command\CreateBoughtTogetherProductAssociationType;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Handler\CreateBoughtTogetherAssociationTypeHandler;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Product\Repository\ProductAssociationTypeRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class CreateBoughtTogetherAssociationTypeHandlerTest extends TestCase
{
    use ProphecyTrait;

    public function testCreatingBoughtTogetherAssociationType(): void
    {
        $productAssociationTypeFactory = $this->prophesize(FactoryInterface::class);
        $product = $this->prophesize(ProductInterface::class);
        $productAssociationTypeFactory->createNew()->willReturn($product);
        $product->setCode('bought_together')->shouldBeCalled();
        $product->setName('Bought together')->shouldBeCalled();

        $productAssociationTypeRepository = $this->prophesize(ProductAssociationTypeRepositoryInterface::class);
        $productAssociationTypeRepository->add($product)->shouldBeCalled();

        $handler = new CreateBoughtTogetherAssociationTypeHandler(
            $productAssociationTypeFactory->reveal(),
            $productAssociationTypeRepository->reveal(),
        );

        $handler(new CreateBoughtTogetherProductAssociationType());
    }
}
