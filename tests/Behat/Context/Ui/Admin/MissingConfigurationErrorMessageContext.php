<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Behat\Element\Admin\MissingConfigurationErrorMessageElementInterface;
use Webmozart\Assert\Assert;

final class MissingConfigurationErrorMessageContext implements Context
{
    public function __construct(
        private MissingConfigurationErrorMessageElementInterface $missingConfigurationErrorMessageElement,
    ) {
    }

    /**
     * @Then I should see the error message about missing configuration
     */
    public function iShouldSeeTheErrorMessageAboutMissingConfiguration(): void
    {
        Assert::true($this->missingConfigurationErrorMessageElement->isErrorMessage());
    }

    /**
     * @Then I should not see the error message about missing configuration
     */
    public function iShouldNotSeeTheErrorMessageAboutMissingConfiguration(): void
    {
        Assert::false($this->missingConfigurationErrorMessageElement->isErrorMessage());
    }
}
