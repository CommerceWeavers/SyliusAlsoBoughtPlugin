<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Checker;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\BoughtTogetherProductsAwareInterface;
use Sylius\Component\Product\Repository\ProductAssociationTypeRepositoryInterface;

final class BoughtTogetherProductAssociationTypeConfiguredChecker implements BoughtTogetherProductAssociationTypeConfiguredCheckerInterface
{
    public function __construct(private ProductAssociationTypeRepositoryInterface $productAssociationTypeRepository)
    {
    }

    public function isConfigured(): bool
    {
        $productAssociationType = $this->productAssociationTypeRepository->findOneBy([
            'code' => BoughtTogetherProductsAwareInterface::BOUGHT_TOGETHER_ASSOCIATION_TYPE_CODE,
        ]);

        return null !== $productAssociationType;
    }
}
