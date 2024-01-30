<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Event;

use Symfony\Component\Uid\Uuid;

final class SynchronizationEnded
{
    /** @param string[] $affectedProducts */
    public function __construct(
        private Uuid $id,
        private \DateTimeImmutable $date,
        private int $numberOfOrders,
        private array $affectedProducts,
    ) {
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function date(): \DateTimeImmutable
    {
        return $this->date;
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
