<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Behat\Context\Api;

use ApiPlatform\Core\Api\IriConverterInterface;
use Behat\Behat\Context\Context;
use Sylius\Behat\Client\ApiClientInterface;
use Sylius\Behat\Client\ResponseCheckerInterface;
use Sylius\Behat\Context\Api\Resources;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductAssociationInterface;
use Webmozart\Assert\Assert;

final class ProductContext implements Context
{
    public function __construct(
        private ApiClientInterface $client,
        private SharedStorageInterface $sharedStorage,
        private ResponseCheckerInterface $responseChecker,
        private IriConverterInterface $iriConverter,
    ) {
    }

    /**
     * @Then /^I should not see the product association "([^"]+)" with (product "[^"]+")$/
     */
    public function iShouldNotSeeTheProductAssociationWithProduct(string $productAssociationName, ProductInterface $associatedProduct): void
    {
        /** @var ProductInterface $product */
        $product = $this->sharedStorage->get('product');

        $response = $this->client->show(Resources::PRODUCTS, (string) $product->getCode());

        /** @var array<string, string> $associations */
        $associations = $this->responseChecker->getValue($response, 'associations');

        $boughtTogetherAssociationIri = $associations[StringInflector::nameToLowercaseCode($productAssociationName)] ?? null;
        Assert::stringNotEmpty($boughtTogetherAssociationIri);

        /** @var ProductAssociationInterface $boughtTogetherAssociation */
        $boughtTogetherAssociation = $this->iriConverter->getItemFromIri($boughtTogetherAssociationIri);

        Assert::false($boughtTogetherAssociation->hasAssociatedProduct($associatedProduct));
    }

    /**
     * @Then I should not see the product association :productAssociationName
     */
    public function iShouldNotSeeTheProductAssociation(string $productAssociationName): void
    {
        /** @var ProductInterface $product */
        $product = $this->sharedStorage->get('product');

        $response = $this->client->show(Resources::PRODUCTS, (string) $product->getCode());

        /** @var array<string, string> $associations */
        $associations = $this->responseChecker->getValue($response, 'associations');

        Assert::keyNotExists($associations, StringInflector::nameToLowercaseCode($productAssociationName));
    }
}
