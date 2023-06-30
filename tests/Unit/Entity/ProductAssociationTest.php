<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Unit\Entity;

use PHPUnit\Framework\TestCase;
use Sylius\Component\Product\Model\ProductAssociation;
use Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Application\Entity\Product;

final class ProductAssociationTest extends TestCase
{
    public function testItCanHaveMultipleProductSetAtOnce(): void
    {
        $firstProduct = new Product();
        $secondProduct = new Product();

        $productAssociation = new ProductAssociation();
        $productAssociation->addAssociatedProduct($firstProduct);
        $productAssociation->addAssociatedProduct($secondProduct);

        self::assertTrue($productAssociation->getAssociatedProducts()->contains($firstProduct));
        self::assertTrue($productAssociation->getAssociatedProducts()->contains($secondProduct));
    }
}
