<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Sylius\Behat\Context\Setup\OrderContext as SyliusOrderContext;
use Sylius\Component\Core\Model\ProductInterface;

final class OrderContext implements Context
{
    public function __construct(private SyliusOrderContext $orderContext)
    {
    }

    /**
     * @Given /^the customer (bought a single "([^"]+)", "([^"]+)" and "([^"]+)")$/
     * @Given /^the customer (bought a single "([^"]+)", "([^"]+)", "([^"]+)" and "([^"]+)")$/
     *
     * @var ProductInterface[] $products
     */
    public function customerBoughtASingle(array $products): void
    {
        foreach ($products as $product) {
            $this->orderContext->theCustomerBoughtSingleProduct($product);
        }
    }
}
