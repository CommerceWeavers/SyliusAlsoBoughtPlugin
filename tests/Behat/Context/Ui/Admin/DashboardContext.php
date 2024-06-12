<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Sylius\Behat\Page\Admin\DashboardPageInterface;
use Sylius\Component\Core\Model\ChannelInterface;

final class DashboardContext implements Context
{
    public function __construct (
        private readonly DashboardPageInterface $dashboardPage,
    ) {
    }

    /**
     * @When I open the :channel channel dashboard
     */
    public function iOpenTheChannelDashboard(ChannelInterface $channel): void
    {
        $this->dashboardPage->open(['channel' => $channel->getCode()]);
    }
}
