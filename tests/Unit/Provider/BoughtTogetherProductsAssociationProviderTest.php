<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Unit\Provider;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Provider\BoughtTogetherProductsAssociationProvider;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Sylius\Component\Product\Model\ProductAssociation;
use Sylius\Component\Product\Model\ProductAssociationType;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Application\Entity\Product;

final class BoughtTogetherProductsAssociationProviderTest extends TestCase
{
    use ProphecyTrait;

    public function testProvidingExistingBoughtTogetherProductsAssociation(): void
    {
        $productAssociationFactory = $this->prophesize(FactoryInterface::class);
        $productAssociationTypeRepository = $this->prophesize(RepositoryInterface::class);
        $product = $this->prophesize(Product::class);
        $productAssociation = $this->prophesize(ProductAssociation::class);

        $provider = new BoughtTogetherProductsAssociationProvider(
            $productAssociationFactory->reveal(),
            $productAssociationTypeRepository->reveal(),
        );

        $product->getBroughtTogetherAssociation()->willReturn($productAssociation->reveal());

        self::assertSame(
            $productAssociation->reveal(),
            $provider->getForProduct($product->reveal())
        );
    }

    public function testProvidingNewBoughtTogetherProductsAssociation(): void
    {
        $productAssociationFactory = $this->prophesize(FactoryInterface::class);
        $productAssociationTypeRepository = $this->prophesize(RepositoryInterface::class);
        $product = $this->prophesize(Product::class);
        $productAssociation = $this->prophesize(ProductAssociation::class);
        $productAssociationType = $this->prophesize(ProductAssociationType::class);

        $provider = new BoughtTogetherProductsAssociationProvider(
            $productAssociationFactory->reveal(),
            $productAssociationTypeRepository->reveal(),
        );

        $product->getBroughtTogetherAssociation()->willReturn(null);

        $productAssociationTypeRepository
            ->findOneBy(['code' => 'bought_together'])
            ->willReturn($productAssociationType->reveal())
        ;
        $productAssociationFactory->createNew()->willReturn($productAssociation->reveal());
        $productAssociationType->setCode('bought_together')->shouldBeCalled();
        $productAssociation->setType($productAssociationType->reveal())->shouldBeCalled();
        $product->addAssociation($productAssociation->reveal())->shouldBeCalled();

        self::assertSame(
            $productAssociation->reveal(),
            $provider->getForProduct($product->reveal())
        );
    }
}
