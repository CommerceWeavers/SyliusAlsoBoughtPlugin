<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Behat\Context\Cli\SynchronizeBoughtTogetherProductsContext as CliSynchronizeBoughtTogetherProductsContext;

final class SynchronizeBoughtTogetherProductsContext implements Context
{
    public function __construct(
        private CliSynchronizeBoughtTogetherProductsContext $cliSynchronizeBoughtTogetherProductsContext
    ) {
    }

    /**
     * @Given bought together products are synchronized
     */
    public function runSynchronizeBoughtTogetherProductsCommand(): void
    {
        $this->cliSynchronizeBoughtTogetherProductsContext->runSynchronizeBoughtTogetherProductsCommand();
    }
}
