<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Provider;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\BoughtTogetherProductsAwareInterface;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Exception\BoughtTogetherAssociationTypeNotFoundException;
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
        Assert::isInstanceOf($product, BoughtTogetherProductsAwareInterface::class);

        /** @var ProductAssociation|null $productAssociation */
        $productAssociation = $product->getBoughtTogetherAssociation();

        if (null !== $productAssociation) {
            return $productAssociation;
        }

        $productAssociationType = $this->productAssociationTypeRepository->findOneBy([
            'code' => BoughtTogetherProductsAwareInterface::BOUGHT_TOGETHER_ASSOCIATION_TYPE_CODE,
        ]);

        if (null === $productAssociationType) {
            throw new BoughtTogetherAssociationTypeNotFoundException();
        }

        $productAssociation = $this->productAssociationFactory->createNew();
        $productAssociation->setType($productAssociationType);
        $product->addAssociation($productAssociation);

        return $productAssociation;
    }
}
