<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Handler;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Cli\CreateBoughtTogetherProductAssociationTypeCommand;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Command\CreateBoughtTogetherProductAssociationType;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\BoughtTogetherProductsAwareInterface;
use Sylius\Component\Product\Model\ProductAssociationTypeInterface;
use Sylius\Component\Product\Repository\ProductAssociationTypeRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CreateBoughtTogetherAssociationTypeHandler
{
    /**
     * @param FactoryInterface<ProductAssociationTypeInterface> $productAssociationTypeFactory
     */
    public function __construct(
        private FactoryInterface $productAssociationTypeFactory,
        private ProductAssociationTypeRepositoryInterface $productAssociationTypeRepository,
    ) {
    }

    public function __invoke(CreateBoughtTogetherProductAssociationType $command): void
    {
        /** @var ProductAssociationTypeInterface $productAssociationType */
        $productAssociationType = $this->productAssociationTypeFactory->createNew();
        $productAssociationType->setCode(BoughtTogetherProductsAwareInterface::BOUGHT_TOGETHER_ASSOCIATION_TYPE_CODE);
        $productAssociationType->setName('Bought together');

        $this->productAssociationTypeRepository->add($productAssociationType);
    }
}
