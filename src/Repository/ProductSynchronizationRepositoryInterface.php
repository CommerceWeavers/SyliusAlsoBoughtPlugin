<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Repository;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\ProductSynchronizationInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface ProductSynchronizationRepositoryInterface extends RepositoryInterface
{
    public function findLastSynchronization(): ?ProductSynchronizationInterface;
}
