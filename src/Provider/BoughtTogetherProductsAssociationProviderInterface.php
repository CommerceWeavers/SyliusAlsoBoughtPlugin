<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Provider;

use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductAssociation;

interface BoughtTogetherProductsAssociationProviderInterface
{
    public function getForProduct(ProductInterface $product): ProductAssociation;
}
