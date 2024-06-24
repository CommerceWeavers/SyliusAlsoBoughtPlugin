<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Synchronizer;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Mapper\BoughtTogetherProductsMapperInterface;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Model\SynchronizationResult;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Provider\PlacedOrdersProviderInterface;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Saver\BoughtTogetherProductsInfoSaverInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class FrequentlyBoughtTogetherProductsSynchronizer implements FrequentlyBoughtTogetherProductsSynchronizerInterface
{
    public function __construct(
        private PlacedOrdersProviderInterface $placedOrdersProvider,
        private BoughtTogetherProductsMapperInterface $boughtTogetherProductsMapper,
        private BoughtTogetherProductsInfoSaverInterface $boughtTogetherProductsInfoSaver,
        private int $batchSize = 1000,
    ) {
    }

    public function synchronize(\DateTimeInterface $since): SynchronizationResult
    {
        $offset = 0;

        $numberOfOrders = 0;
        $affectedProducts = [];

        while (($orders = $this->placedOrdersProvider->getSince($since, $this->batchSize, $offset)) !== []) {
            $affectedProducts = array_merge($affectedProducts, $this->getAffectedProducts($orders));
            $numberOfOrders += count($orders);

            $offset += $this->batchSize;
        }

        foreach ($affectedProducts as $productCode => $products) {
            $this->boughtTogetherProductsInfoSaver->save($productCode, $products);
        }

        return new SynchronizationResult($numberOfOrders, array_keys($affectedProducts));
    }

    /**
     * @param OrderInterface[] $orders
     *
     * @return array<string, array<string>>
     */
    private function getAffectedProducts(array $orders): array
    {
        $affectedProducts = [];

        foreach ($orders as $order) {
            $map = $this->boughtTogetherProductsMapper->map($order);

            foreach ($map as $productCode => $products) {
                $affectedProducts[$productCode] = array_merge($affectedProducts[$productCode] ?? [], $products);
            }
        }

        return $affectedProducts;
    }
}
