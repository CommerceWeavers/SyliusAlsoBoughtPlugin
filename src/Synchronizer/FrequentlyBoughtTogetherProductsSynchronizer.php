<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Synchronizer;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Mapper\BoughtTogetherProductsMapperInterface;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Model\SynchronizationResult;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Provider\PlacedOrdersProviderInterface;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Saver\BoughtTogetherProductsInfoSaverInterface;

final class FrequentlyBoughtTogetherProductsSynchronizer implements FrequentlyBoughtTogetherProductsSynchronizerInterface
{
    public function __construct(
        private PlacedOrdersProviderInterface $placedOrdersProvider,
        private BoughtTogetherProductsMapperInterface $boughtTogetherProductsMapper,
        private BoughtTogetherProductsInfoSaverInterface $boughtTogetherProductsInfoSaver,
    ) {
    }

    public function synchronize(\DateTimeInterface $since): SynchronizationResult
    {
        $orders = $this->placedOrdersProvider->getSince($since);

        $affectedProducts = [];

        foreach ($orders as $order) {
            $map = $this->boughtTogetherProductsMapper->map($order);
            foreach ($map as $productCode => $products) {
                $affectedProducts[$productCode] = array_merge($affectedProducts[$productCode] ?? [], $products);
            }
        }

        foreach ($affectedProducts as $productCode => $products) {
            $this->boughtTogetherProductsInfoSaver->save($productCode, $products);
        }

        return new SynchronizationResult(count($orders), array_keys($affectedProducts));
    }
}
