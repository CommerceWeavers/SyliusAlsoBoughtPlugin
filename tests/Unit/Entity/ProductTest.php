<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Unit\Entity;

use PHPUnit\Framework\TestCase;
use Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Application\Entity\Product;

final class ProductTest extends TestCase
{
    public function testProductHasInformationAboutFrequentlyBoughtOtherProducts(): void
    {
        $product = new Product();
        $product->increaseBoughtTogetherProductsCount(['500', '12345', '12345']);
        self::assertSame($product->getBoughtTogetherProducts(), ['12345' => 2, '500' => 1]);

        $product->increaseBoughtTogetherProductsCount(['500', '222', '500']);
        self::assertSame($product->getBoughtTogetherProducts(), ['500' => 3, '12345' => 2, '222' => 1]);
    }
}
