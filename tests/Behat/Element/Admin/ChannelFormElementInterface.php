<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Behat\Element\Admin;

interface ChannelFormElementInterface
{
    public function changeNumberOfSynchronisedProducts(int $number): void;

    public function getNumberOfSynchronisedProducts(): int;
}
