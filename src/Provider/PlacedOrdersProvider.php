<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Provider;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;

final class PlacedOrdersProvider implements PlacedOrdersProviderInterface
{
    public function __construct(private OrderRepositoryInterface $orderRepository)
    {
    }

    public function getSince(\DateTimeInterface $since): array
    {
        /** @var OrderInterface[] $result */
        $result = $this->orderRepository
            ->createListQueryBuilder()
            ->andWhere('o.checkoutCompletedAt >= :date')
            ->setParameter('date', $since)
            ->getQuery()
            ->getResult()
        ;

        return $result;
    }
}
