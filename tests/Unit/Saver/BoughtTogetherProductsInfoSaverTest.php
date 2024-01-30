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

        $productRepository->findOneByCode('first_product_code')->willReturn($product->reveal());
        $product->increaseBoughtTogetherProductsCount(['second_product_code', 'third_product_code'])->shouldBeCalled();

        $saver->save('first_product_code', ['second_product_code', 'third_product_code']);
    }
}
