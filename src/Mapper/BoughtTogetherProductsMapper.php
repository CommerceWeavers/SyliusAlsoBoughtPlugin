<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Mapper;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;

final class BoughtTogetherProductsMapper implements BoughtTogetherProductsMapperInterface
{
    public function map(OrderInterface $order): array
    {
        $map = [];

        /** @var string[] $productCodes */
        $productCodes = $order
            ->getItems()
            ->map(fn (OrderItemInterface $item): ?string => $item->getProduct()?->getCode())
            ->filter(fn (?string $code): bool => $code !== null)
            ->toArray()
        ;

        sort($productCodes);

        foreach ($productCodes as $productCode) {
            $map[$productCode] = array_values(array_diff($productCodes, [$productCode]));
        }

        return $map;
    }
}
