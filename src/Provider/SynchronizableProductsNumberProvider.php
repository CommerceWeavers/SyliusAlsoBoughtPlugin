<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Provider;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\BoughtTogetherConfigurableChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;

final class SynchronizableProductsNumberProvider implements SynchronizableProductsNumberProviderInterface
{
    public function __construct(private int $defaultNumberOfProducts = 10)
    {
    }

    public function getNumberOfProductsToSynchronise(ProductInterface $product): int
    {
        $channels = $product->getChannels();

        $numberOfProducts = 0;
        /** @var BoughtTogetherConfigurableChannelInterface $channel */
        foreach ($channels as $channel) {
            $numberOfProducts = max($numberOfProducts, $channel->getNumberOfSynchronisedProducts());
        }

        if ($numberOfProducts === 0) {
            return $this->defaultNumberOfProducts;
        }

        return $numberOfProducts;
    }
}
