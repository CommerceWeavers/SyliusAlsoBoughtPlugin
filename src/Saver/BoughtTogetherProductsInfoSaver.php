<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Saver;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\BoughtTogetherProductsAwareInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;

final class BoughtTogetherProductsInfoSaver implements BoughtTogetherProductsInfoSaverInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function save(string $productCode, array $productCodes): void
    {
        /** @var ProductInterface&BoughtTogetherProductsAwareInterface $product */
        $product = $this->productRepository->findOneByCode($productCode);

        $product->increaseBoughtTogetherProductsCount($productCodes);

        $this->entityManager->flush();
    }
}
