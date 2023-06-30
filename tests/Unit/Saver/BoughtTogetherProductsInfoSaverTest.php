<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Unit\Saver;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Saver\BoughtTogetherProductsInfoSaver;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Application\Entity\Product;

final class BoughtTogetherProductsInfoSaverTest extends TestCase
{
    use ProphecyTrait;

    public function testSavingProductsBoughtTogetherInformation(): void
    {
        $productRepository = $this->prophesize(ProductRepositoryInterface::class);
        $entityManager = $this->prophesize(EntityManagerInterface::class);
        $product = $this->prophesize(Product::class);

        $saver = new BoughtTogetherProductsInfoSaver($productRepository->reveal(), $entityManager->reveal());

        $productRepository->findOneByCode('10273')->willReturn($product->reveal());
        $product->increaseBoughtTogetherProductsCount(['12182', '12183'])->shouldBeCalled();

        $saver->save('10273', ['12182', '12183']);
    }
}
