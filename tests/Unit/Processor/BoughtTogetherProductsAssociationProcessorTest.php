<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Unit\Processor;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Event\SynchronizationEnded;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Processor\BoughtTogetherProductsAssociationProcessor;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Provider\BoughtTogetherProductsAssociationProviderInterface;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Provider\SynchronizableProductsNumberProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Product\Model\ProductAssociation;
use Symfony\Component\Uid\Uuid;
use Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Application\Entity\Product;

final class BoughtTogetherProductsAssociationProcessorTest extends TestCase
{
    use ProphecyTrait;

    public function testProcessingBoughtTogetherProductsIntoAssociation(): void
    {
        $entityManager = $this->prophesize(EntityManagerInterface::class);
        $productRepository = $this->prophesize(ProductRepositoryInterface::class);
        $boughtTogetherProductsAssociationProvider = $this->prophesize(BoughtTogetherProductsAssociationProviderInterface::class);
        $numberOfSynchronizableProductsProvider = $this->prophesize(SynchronizableProductsNumberProviderInterface::class);

        $processor = new BoughtTogetherProductsAssociationProcessor(
            $entityManager->reveal(),
            $productRepository->reveal(),
            $boughtTogetherProductsAssociationProvider->reveal(),
            $numberOfSynchronizableProductsProvider->reveal(),
        );

        $product = $this->prophesize(Product::class);
        $productAssociation = $this->prophesize(ProductAssociation::class);

        $boughtTogetherProductsAssociationProvider->getForProduct($product->reveal())->willReturn($productAssociation);
        $product->getBoughtTogetherProducts()->willReturn(['12345' => 2, '500' => 1]);

        $numberOfSynchronizableProductsProvider->getNumberOfProductsToSynchronise($product->reveal())->willReturn(10);

        $firstAssociatedProduct = new Product();
        $firstAssociatedProduct->setCode('12345');
        $secondAssociatedProduct = new Product();
        $secondAssociatedProduct->setCode('500');

        $productRepository->findBy(['code' => ['10137']])->willReturn([$product]);
        $productRepository->findBy(['code' => ['12345', '500']])->willReturn([$firstAssociatedProduct, $secondAssociatedProduct]);

        $productAssociation->clearAssociatedProducts()->shouldBeCalled();
        $productAssociation->addAssociatedProduct($firstAssociatedProduct)->shouldBeCalled();
        $productAssociation->addAssociatedProduct($secondAssociatedProduct)->shouldBeCalled();

        $entityManager->persist($productAssociation)->shouldBeCalled();
        $entityManager->flush()->shouldBeCalledOnce();

        $processor(new SynchronizationEnded(Uuid::v4(), new \DateTimeImmutable(), 10, ['10137']));
    }

    public function testItFlushesProgressInBatches(): void
    {
        $entityManager = $this->prophesize(EntityManagerInterface::class);
        $productRepository = $this->prophesize(ProductRepositoryInterface::class);
        $boughtTogetherProductsAssociationProvider = $this->prophesize(BoughtTogetherProductsAssociationProviderInterface::class);
        $numberOfSynchronizableProductsProvider = $this->prophesize(SynchronizableProductsNumberProviderInterface::class);

        $processor = new BoughtTogetherProductsAssociationProcessor(
            $entityManager->reveal(),
            $productRepository->reveal(),
            $boughtTogetherProductsAssociationProvider->reveal(),
            $numberOfSynchronizableProductsProvider->reveal(),
            1,
        );

        $firstProduct = $this->prophesize(Product::class);
        $secondProduct = $this->prophesize(Product::class);
        $firstProductAssociation = $this->prophesize(ProductAssociation::class);
        $secondProductAssociation = $this->prophesize(ProductAssociation::class);

        $boughtTogetherProductsAssociationProvider->getForProduct($firstProduct->reveal())->willReturn($firstProductAssociation);
        $firstProduct->getBoughtTogetherProducts()->willReturn(['12345' => 2, '500' => 1]);

        $boughtTogetherProductsAssociationProvider->getForProduct($secondProduct->reveal())->willReturn($secondProductAssociation);
        $secondProduct->getBoughtTogetherProducts()->willReturn(['10' => 2, '20' => 1]);

        $numberOfSynchronizableProductsProvider->getNumberOfProductsToSynchronise($firstProduct->reveal())->willReturn(10);
        $numberOfSynchronizableProductsProvider->getNumberOfProductsToSynchronise($secondProduct->reveal())->willReturn(10);

        $firstAssociatedProduct = new Product();
        $firstAssociatedProduct->setCode('12345');
        $secondAssociatedProduct = new Product();
        $secondAssociatedProduct->setCode('500');
        $thirdAssociatedProduct = new Product();
        $thirdAssociatedProduct->setCode('10');
        $fourthAssociatedProduct = new Product();
        $fourthAssociatedProduct->setCode('20');

        $productRepository->findBy(['code' => ['10137', '12345']])->willReturn([$firstProduct, $secondProduct]);
        $productRepository->findBy(['code' => ['12345', '500']])->willReturn([$firstAssociatedProduct, $secondAssociatedProduct]);
        $productRepository->findBy(['code' => ['10', '20']])->willReturn([$thirdAssociatedProduct, $fourthAssociatedProduct]);

        $firstProductAssociation->clearAssociatedProducts()->shouldBeCalled();
        $firstProductAssociation->addAssociatedProduct($firstAssociatedProduct)->shouldBeCalled();
        $firstProductAssociation->addAssociatedProduct($secondAssociatedProduct)->shouldBeCalled();

        $secondProductAssociation->clearAssociatedProducts()->shouldBeCalled();
        $secondProductAssociation->addAssociatedProduct($thirdAssociatedProduct)->shouldBeCalled();
        $secondProductAssociation->addAssociatedProduct($fourthAssociatedProduct)->shouldBeCalled();

        $entityManager->persist($firstProductAssociation)->shouldBeCalled();
        $entityManager->persist($secondProductAssociation)->shouldBeCalled();
        $entityManager->flush()->shouldBeCalledTimes(3);

        $processor(new SynchronizationEnded(Uuid::v4(), new \DateTimeImmutable(), 10, ['10137', '12345']));
    }
}
