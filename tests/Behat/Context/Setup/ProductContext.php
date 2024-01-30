<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Sylius\Behat\Context\Setup\ProductContext as SyliusProductContext;

final class ProductContext implements Context
{
    public function __construct(private SyliusProductContext $productContext)
    {
    }

    /**
     * @Given /^the store has products "([^"]+)", "([^"]+)", "([^"]+)" and "([^"]+)"$/
     * @Given /^the store has products "([^"]+)", "([^"]+)", "([^"]+)", "([^"]+)", "([^"]+)", "([^"]+)", "([^"]+)" and "([^"]+)"$/
     */
    public function storeHasProducts(string ...$products): void
    {
        foreach ($products as $product) {
            $this->productContext->storeHasAProductPricedAt($product);
        }
    }
}
