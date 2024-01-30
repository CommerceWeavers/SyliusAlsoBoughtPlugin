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
        $product->increaseBoughtTogetherProductsCount(['first_product_code', 'second_product_code', 'second_product_code']);
        self::assertSame($product->getBoughtTogetherProducts(), ['second_product_code' => 2, 'first_product_code' => 1]);

        $product->increaseBoughtTogetherProductsCount(['first_product_code', 'third_product_code', 'first_product_code']);
        self::assertSame($product->getBoughtTogetherProducts(), ['first_product_code' => 3, 'second_product_code' => 2, 'third_product_code' => 1]);
    }
}
