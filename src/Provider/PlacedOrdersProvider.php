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

    public function getSince(\DateTimeInterface $since, int $limit, int $offset): array
    {
        /** @var OrderInterface[] $result */
        $result = $this->orderRepository
            ->createListQueryBuilder()
            ->andWhere('o.checkoutCompletedAt >= :date')
            ->setParameter('date', $since)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
        ;

        return $result;
    }
}
