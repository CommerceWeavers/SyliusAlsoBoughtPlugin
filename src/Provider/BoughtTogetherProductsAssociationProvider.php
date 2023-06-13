<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Provider;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\BroughtTogetherProductsAwareInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductAssociation;
use Sylius\Component\Product\Model\ProductAssociationType;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

final class BoughtTogetherProductsAssociationProvider implements BoughtTogetherProductsAssociationProviderInterface
{
    /**
     * @param FactoryInterface<ProductAssociation> $productAssociationFactory
     * @param RepositoryInterface<ProductAssociationType> $productAssociationTypeRepository
     */
    public function __construct(
        private FactoryInterface $productAssociationFactory,
        private RepositoryInterface $productAssociationTypeRepository,
    ) {
    }

    public function getForProduct(ProductInterface $product): ProductAssociation
    {
        Assert::isInstanceOf($product, BroughtTogetherProductsAwareInterface::class);

        /** @var ProductAssociation|null $productAssociation */
        $productAssociation = $product->getBroughtTogetherAssociation();

        if (null !== $productAssociation) {
            return $productAssociation;
        }

        $productAssociationType = $this->productAssociationTypeRepository->findOneBy([
            'code' => BroughtTogetherProductsAwareInterface::BOUGHT_TOGETHER_ASSOCIATION_TYPE_CODE,
        ]);

        Assert::notNull($productAssociationType);

        $productAssociationType->setCode(
            BroughtTogetherProductsAwareInterface::BOUGHT_TOGETHER_ASSOCIATION_TYPE_CODE,
        );

        $productAssociation = $this->productAssociationFactory->createNew();
        $productAssociation->setType($productAssociationType);
        $product->addAssociation($productAssociation);

        return $productAssociation;
    }
}
