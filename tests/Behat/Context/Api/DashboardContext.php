<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Behat\Context\Api;

use Behat\Behat\Context\Context;

final class DashboardContext implements Context
{
    /**
     * @When I open the :channel channel dashboard
     */
    public function iOpenTheChannelDashboard(string $channelName): void
    {
        // intentionally left blank
    }
}
