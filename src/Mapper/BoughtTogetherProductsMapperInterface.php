<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Mapper;

use Sylius\Component\Core\Model\OrderInterface;

interface BoughtTogetherProductsMapperInterface
{
    /** @return array<string, array<string>> */
    public function map(OrderInterface $order): array;
}
