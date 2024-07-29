<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Provider;

use Sylius\Component\Core\Model\OrderInterface;

interface PlacedOrdersProviderInterface
{
    /** @return OrderInterface[] */
    public function getSince(\DateTimeInterface $since, int $limit, int $offset): array;
}
