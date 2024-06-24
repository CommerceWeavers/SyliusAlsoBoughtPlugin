<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Behat\Element\Admin\ChannelFormElementInterface;
use Webmozart\Assert\Assert;

final class ManagingChannelsContext implements Context
{
    public function __construct(private readonly ChannelFormElementInterface $channelFormElement)
    {
    }

    /**
     * @When I change the number of synchronised products to :number
     */
    public function iChangeTheNumberOfSynchronisedProductsTo(int $number): void
    {
        $this->channelFormElement->changeNumberOfSynchronisedProducts($number);
    }

    /**
     * @Then the number of synchronised products should be :numner
     */
    public function theNumberOfSynchronisedProductsShouldBe(int $number): void
    {
        Assert::same($number, $this->channelFormElement->getNumberOfSynchronisedProducts());
    }
}
