<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Model;

final class SynchronizationResult
{
    /** @param string[] $affectedProducts */
    public function __construct(
        private int $numberOfOrders,
        private array $affectedProducts,
    ) {
    }

    public function numberOfOrders(): int
    {
        return $this->numberOfOrders;
    }

    public function affectedProducts(): array
    {
        return $this->affectedProducts;
    }
}
