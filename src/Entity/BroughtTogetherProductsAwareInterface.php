<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Entity;

use Sylius\Component\Product\Model\ProductAssociationInterface;

interface BroughtTogetherProductsAwareInterface
{
    public const BOUGHT_TOGETHER_ASSOCIATION_TYPE_CODE = 'bought_together';

    public function getBoughtTogetherProducts(): array;

    public function increaseBoughtTogetherProductsCount(array $codes): void;

    public function getBroughtTogetherAssociation(): ?ProductAssociationInterface;
}
