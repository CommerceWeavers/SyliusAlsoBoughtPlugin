<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Unit\Provider;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Provider\PlacedOrdersProvider;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Sylius\Component\Core\Model\Order;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;

final class PlacedOrdersProviderTest extends TestCase
{
    use ProphecyTrait;

    public function testItProvidesOrdersPlacedSinceSpecificDate(): void
    {
        $orderRepository = $this->prophesize(OrderRepositoryInterface::class);
        $queryBuilder = $this->prophesize(QueryBuilder::class);
        $query = $this->prophesize(AbstractQuery::class);

        $firstOrder = new Order();
        $secondOrder = new Order();

        $since = new \DateTimeImmutable('2020-01-01');

        $orderRepository->createListQueryBuilder()->willReturn($queryBuilder->reveal())->shouldBeCalled();
        $queryBuilder->andWhere('o.checkoutCompletedAt >= :date')->willReturn($queryBuilder->reveal())->shouldBeCalled();
        $queryBuilder->setParameter('date', $since)->willReturn($queryBuilder->reveal())->shouldBeCalled();
        $queryBuilder->getQuery()->willReturn($query->reveal())->shouldBeCalled();
        $query->getResult()->willReturn([$firstOrder, $secondOrder]);

        self::assertSame(
            [$firstOrder, $secondOrder],
            (new PlacedOrdersProvider($orderRepository->reveal()))->getSince($since)
        );
    }
}
