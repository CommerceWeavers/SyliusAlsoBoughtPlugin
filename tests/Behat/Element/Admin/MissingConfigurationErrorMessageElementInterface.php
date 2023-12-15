<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Behat\Element\Admin;

interface MissingConfigurationErrorMessageElementInterface
{
    public function hasErrorMessage(): bool;
}
