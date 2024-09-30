<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Provider;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\BoughtTogetherProductsAwareInterface;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Exception\BoughtTogetherAssociationTypeNotFoundException;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductAssociation;
use Sylius\Component\Product\Model\ProductAssociationInterface;
use Sylius\Component\Product\Model\ProductAssociationType;
use Sylius\Component\Product\Model\ProductAssociationTypeInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

final class BoughtTogetherProductsAssociationProvider implements BoughtTogetherProductsAssociationProviderInterface
{
    /**
     * @implements FactoryInterface<ProductAssociation> $productAssociationFactory
     * @implements RepositoryInterface<ProductAssociationType> $productAssociationTypeRepository
     */
    public function __construct(
        private readonly FactoryInterface $productAssociationFactory,
        private readonly RepositoryInterface $productAssociationTypeRepository,
    ) {
    }

    public function getForProduct(ProductInterface $product): ProductAssociationInterface
    {
        Assert::isInstanceOf($product, BoughtTogetherProductsAwareInterface::class);

        /** @var ProductAssociation|null $productAssociation */
        $productAssociation = $product->getBoughtTogetherAssociation();

        if (null !== $productAssociation) {
            return $productAssociation;
        }

        /** @var ProductAssociationTypeInterface|null $productAssociationType */
        $productAssociationType = $this->productAssociationTypeRepository->findOneBy([
            'code' => BoughtTogetherProductsAwareInterface::BOUGHT_TOGETHER_ASSOCIATION_TYPE_CODE,
        ]);

        if (null === $productAssociationType) {
            throw new BoughtTogetherAssociationTypeNotFoundException();
        }

        /** @var ProductAssociationInterface $productAssociation */
        $productAssociation = $this->productAssociationFactory->createNew();
        $productAssociation->setType($productAssociationType);
        $product->addAssociation($productAssociation);

        return $productAssociation;
    }
}
