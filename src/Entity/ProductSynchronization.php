<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Entity;

use Symfony\Component\Uid\Uuid;

class ProductSynchronization implements ProductSynchronizationInterface
{
    protected Uuid $id;

    protected \DateTimeInterface $startDate;

    protected ?\DateTimeInterface $endDate = null;

    protected int $numberOfOrders = 0;

    protected array $affectedProducts = [];

    public function __construct(Uuid $id, \DateTimeInterface $startDate)
    {
        $this->id = $id;
        $this->startDate = $startDate;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getStartDate(): \DateTimeInterface
    {
        return $this->startDate;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function end(\DateTimeInterface $endDate, int $numberOfOrders, array $affectedProducts): void
    {
        $this->endDate = $endDate;
        $this->numberOfOrders = $numberOfOrders;
        $this->affectedProducts = $affectedProducts;
    }

    public function getNumberOfOrders(): int
    {
        return $this->numberOfOrders;
    }

    public function getAffectedProducts(): array
    {
        return $this->affectedProducts;
    }
}
