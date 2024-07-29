<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Processor;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\BoughtTogetherProductsAwareInterface;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Event\SynchronizationEnded;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Provider\BoughtTogetherProductsAssociationProviderInterface;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Provider\SynchronizableProductsNumberProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Webmozart\Assert\Assert;

#[AsMessageHandler]
final class BoughtTogetherProductsAssociationProcessor
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ProductRepositoryInterface $productRepository,
        private BoughtTogetherProductsAssociationProviderInterface $boughtTogetherProductsAssociationProvider,
        private SynchronizableProductsNumberProviderInterface $synchronizableProductsNumberProvider,
        private int $batchSize = 100,
    ) {
    }

    public function __invoke(SynchronizationEnded $event): void
    {
        /** @var ProductInterface[] $products */
        $products = $this->findProductsByCodes($event->affectedProducts());

        Assert::allIsInstanceOf($products, BoughtTogetherProductsAwareInterface::class);

        $batch = 0;
        foreach ($products as $product) {
            $boughtTogetherProducts = array_slice(
                $product->getBoughtTogetherProducts(),
                0,
                $this->synchronizableProductsNumberProvider->getNumberOfProductsToSynchronise($product) - 1,
                true,
            );
            $boughtTogetherAssociation = $this->boughtTogetherProductsAssociationProvider->getForProduct($product);
            $this->entityManager->persist($boughtTogetherAssociation);

            $frequentlyBoughtTogetherProducts = $this->findProductsByCodes(array_keys($boughtTogetherProducts));
            $boughtTogetherAssociation->clearAssociatedProducts();

            foreach ($frequentlyBoughtTogetherProducts as $frequentlyBoughtTogetherProduct) {
                $boughtTogetherAssociation->addAssociatedProduct($frequentlyBoughtTogetherProduct);
            }

            if (++$batch >= $this->batchSize) {
                $this->entityManager->flush();
                $batch = 0;
            }
        }

        $this->entityManager->flush();
    }

    /** @return ProductInterface[] */
    private function findProductsByCodes(array $codes): array
    {
        /** @var ProductInterface[] $products */
        $products = $this->productRepository->findBy(['code' => $codes]);

        return $products;
    }
}
