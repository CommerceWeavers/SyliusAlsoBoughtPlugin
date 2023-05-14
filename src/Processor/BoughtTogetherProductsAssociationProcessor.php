<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Processor;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\BroughtTogetherProductsAwareInterface;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Event\SynchronizationEnded;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Provider\BoughtTogetherProductsAssociationProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Webmozart\Assert\Assert;

final class BoughtTogetherProductsAssociationProcessor implements MessageHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ProductRepositoryInterface $productRepository,
        private BoughtTogetherProductsAssociationProviderInterface $boughtTogetherProductsAssociationProvider,
        private int $numberOfProductsToAssociate = 10,
    ) {
    }

    public function __invoke(SynchronizationEnded $event): void
    {
        /** @var ProductInterface[] $products */
        $products = $this->findProductsByCodes($event->affectedProducts());

        Assert::allIsInstanceOf($products, BroughtTogetherProductsAwareInterface::class);

        foreach ($products as $product) {
            $boughtTogetherProducts = array_slice($product->getBoughtTogetherProducts(), 0, $this->numberOfProductsToAssociate - 1, true);
            $boughtTogetherAssociation = $this->boughtTogetherProductsAssociationProvider->getForProduct($product);

            $frequentlyBoughtTogetherProducts = $this->findProductsByCodes(array_keys($boughtTogetherProducts));
            $boughtTogetherAssociation->clearAssociatedProducts();

            foreach ($frequentlyBoughtTogetherProducts as $frequentlyBoughtTogetherProduct) {
                $boughtTogetherAssociation->addAssociatedProduct($frequentlyBoughtTogetherProduct);
            }
        }

        $this->entityManager->flush();
    }

    /**
     * @return ProductInterface[]
     */
    private function findProductsByCodes(array $codes): array
    {
        /** @var ProductInterface[] $products */
        $products = $this->productRepository->findBy(['code' => $codes]);

        return $products;
    }
}
