<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Sylius\Behat\Context\Ui\Shop\ProductContext as BaseProductContext;
use Sylius\Component\Core\Model\ProductInterface;

final class ProductContext implements Context
{
    public function __construct (
        private readonly BaseProductContext $productContext,
    ) {
    }

    /**
     * @Then /^I should(?:| also) not be able to see the product association "([^"]+)" with (product "[^"]+")$/
     */
    public function iShouldNotBeAbleToSeeTheProductAssociationWithProduct(string $productAssociationName, ProductInterface $product): void
    {
        $this->productContext->iShouldNotSeeTheProductAssociationWithProduct($productAssociationName, $product);
    }

    /**
     * @Then I should not be able to see the product association :productAssociationName
     */
    public function iShouldNotBeAbleToSeeTheProductAssociation(string $productAssociationName): void
    {
        $this->productContext->iShouldNotSeeTheProductAssociationWithProducts($productAssociationName);
    }
}
