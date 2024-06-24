<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Provider;

use Sylius\Component\Core\Model\ProductInterface;

interface SynchronizableProductsNumberProviderInterface
{
    public function getNumberOfProductsToSynchronise(ProductInterface $product): int;
}
