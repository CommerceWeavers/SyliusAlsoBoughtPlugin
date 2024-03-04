<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Behat\Context\Transform;

use Behat\Behat\Context\Context;
use Sylius\Behat\Context\Transform\ProductContext as SyliusProductContext;
use Sylius\Component\Core\Model\ProductInterface;

final class ProductContext implements Context
{
    public function __construct(private SyliusProductContext $productContext)
    {
    }

    /**
     * @Transform /^bought a single "([^"]+)", "([^"]+)" and "([^"]+)"$/
     * @Transform /^bought a single "([^"]+)", "([^"]+)", "([^"]+)" and "([^"]+)"$/
     *
     * @return ProductInterface[]
     */
    public function getProductsByNames(string ...$productsNames): array
    {
        return $this->productContext->getProductsByNames(...$productsNames);
    }
}
