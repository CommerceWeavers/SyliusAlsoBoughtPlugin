<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Mapper;

use Sylius\Component\Core\Model\OrderInterface;

final class BoughtTogetherProductsMapper implements BoughtTogetherProductsMapperInterface
{
    public function map(OrderInterface $order): array
    {
        $map = [];
        $productCodes = [];

        foreach ($order->getItems() as $item) {
            $productCode = $item->getProduct()?->getCode();

            if ($productCode === null) {
                continue;
            }

            $productCodes[] = $productCode;
        }

        foreach ($productCodes as $productCode) {
            $map[$productCode] = array_diff($productCodes, [$productCode]);

            sort($map[$productCode]);
        }

        ksort($map);

        return $map;
    }
}
