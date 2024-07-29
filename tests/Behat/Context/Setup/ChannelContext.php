<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\BoughtTogetherConfigurableChannelInterface;
use Doctrine\ORM\EntityManagerInterface;

final class ChannelContext implements Context
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @Given the channel :channel has :number configured as number of synchronizable bought together products
     */
    public function theChannelHasConfiguredAsNumberOfSynchronizableBoughtTogetherProducts(
        BoughtTogetherConfigurableChannelInterface $channel,
        int $number,
    ): void {
        $channel->setNumberOfSynchronisedProducts($number);

        $this->entityManager->flush();
    }
}
