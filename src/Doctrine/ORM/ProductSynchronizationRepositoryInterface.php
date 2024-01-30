<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Doctrine\ORM;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\ProductSynchronizationInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/** @extends RepositoryInterface<ProductSynchronizationInterface> */
interface ProductSynchronizationRepositoryInterface extends RepositoryInterface
{
    public function findLastSynchronization(): ?ProductSynchronizationInterface;
}
