<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Entity;

interface BoughtTogetherConfigurableChannelInterface
{
    public function setNumberOfSynchronisedProducts(int $number): void;

    public function getNumberOfSynchronisedProducts(): int;
}
