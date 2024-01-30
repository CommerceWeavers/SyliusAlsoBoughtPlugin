<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Behat\Context\Transform;

use Behat\Behat\Context\Context;
use Sylius\Behat\Context\Transform\ProductContext as SyliusProductContext;

final class ProductContext implements Context
{
    public function __construct(SyliusProductContext $productContext)
    {
    }
    
    public function getProductsByNames(...$productsNames)
    {
        $this->getProductsByNames($productsNames);
    }
}
