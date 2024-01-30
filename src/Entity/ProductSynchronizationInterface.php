<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

interface ProductSynchronizationInterface extends ResourceInterface
{
    public function getStartDate(): \DateTimeInterface;

    public function getEndDate(): ?\DateTimeInterface;

    public function end(\DateTimeInterface $endDate, int $numberOfOrders, array $affectedProducts): void;

    public function getNumberOfOrders(): int;

    public function getAffectedProducts(): array;
}
