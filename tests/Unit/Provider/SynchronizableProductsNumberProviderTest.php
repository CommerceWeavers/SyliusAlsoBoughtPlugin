<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Unit\Provider;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\BoughtTogetherConfigurableChannelInterface;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\BoughtTogetherConfigurableChannelTrait;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Provider\SynchronizableProductsNumberProvider;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Core\Model\Product;

final class SynchronizableProductsNumberProviderTest extends TestCase
{
    public function testItProvidesNumberOfProductsToSynchroniseFromProductChannel(): void
    {
        $product = new Product();
        $channel = new class extends Channel implements BoughtTogetherConfigurableChannelInterface {
            use BoughtTogetherConfigurableChannelTrait;
        };
        $channel->setNumberOfSynchronisedProducts(5);
        $product->addChannel($channel);

        $provider = new SynchronizableProductsNumberProvider();
        $this->assertSame(5, $provider->getNumberOfProductsToSynchronise($product));
    }

    public function testItProvidesTheHighestOfConfiguredNumbers(): void
    {
        $product = new Product();
        $firstChannel = new class extends Channel implements BoughtTogetherConfigurableChannelInterface {
            use BoughtTogetherConfigurableChannelTrait;
        };
        $firstChannel->setNumberOfSynchronisedProducts(5);
        $product->addChannel($firstChannel);
        $secondChannel = new class extends Channel implements BoughtTogetherConfigurableChannelInterface {
            use BoughtTogetherConfigurableChannelTrait;
        };
        $secondChannel->setNumberOfSynchronisedProducts(10);
        $product->addChannel($secondChannel);
        $thirdChannel = new class extends Channel implements BoughtTogetherConfigurableChannelInterface {
            use BoughtTogetherConfigurableChannelTrait;
        };
        $thirdChannel->setNumberOfSynchronisedProducts(7);
        $product->addChannel($thirdChannel);

        $provider = new SynchronizableProductsNumberProvider();
        $this->assertSame(10, $provider->getNumberOfProductsToSynchronise($product));
    }

    public function testItProvidesTheDefaultNumberOfProductsIfNoChannelConfigured(): void
    {
        $product = new Product();

        $provider = new SynchronizableProductsNumberProvider();
        $this->assertSame(10, $provider->getNumberOfProductsToSynchronise($product));
    }

    public function testItProvidesTheCustomDefaultNumberOfProductsIfNoChannelConfigured(): void
    {
        $product = new Product();

        $provider = new SynchronizableProductsNumberProvider(15);
        $this->assertSame(15, $provider->getNumberOfProductsToSynchronise($product));
    }
}
