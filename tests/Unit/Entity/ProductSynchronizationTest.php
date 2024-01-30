<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Unit\Entity;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\ProductSynchronization;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class ProductSynchronizationTest extends TestCase
{
    public function testItCanBeEnded(): void
    {
        $productSynchronization = new ProductSynchronization(Uuid::v4(), new \DateTime('2020-01-01'));
        $productSynchronization->end(new \DateTime('2021-01-01'), 10, ['123', '456']);

        self::assertEquals(new \DateTime('2021-01-01'), $productSynchronization->getEndDate());
        self::assertSame(10, $productSynchronization->getNumberOfOrders());
        self::assertSame(['123', '456'], $productSynchronization->getAffectedProducts());
    }
}
