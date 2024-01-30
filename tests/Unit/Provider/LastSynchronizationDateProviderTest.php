<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Unit\Provider;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Doctrine\ORM\ProductSynchronizationRepositoryInterface;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\ProductSynchronization;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Provider\LastSynchronizationDateProvider;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Uid\Uuid;

final class LastSynchronizationDateProviderTest extends TestCase
{
    use ProphecyTrait;

    public function testItProvidesLastSynchronizationDateBasedOnProductSynchronizationLog(): void
    {
        $productSynchronizationRepository = $this->prophesize(ProductSynchronizationRepositoryInterface::class);

        $productSynchronization = new ProductSynchronization(Uuid::v4(), new \DateTime('01-01-2023 01:00:10'));
        $productSynchronization->end(new \DateTime('01-01-2023 01:20:10'), 10, ['123', '234']);
        $productSynchronizationRepository->findLastSynchronization()->willReturn($productSynchronization);

        self::assertEquals(
            new \DateTime('01-01-2023 01:20:10'),
            (new LastSynchronizationDateProvider($productSynchronizationRepository->reveal()))->provide()
        );
    }

    public function testItProvidesEarliestPossibleDateIfThereIsNoSynchronizationLog(): void
    {
        $productSynchronizationRepository = $this->prophesize(ProductSynchronizationRepositoryInterface::class);

        $productSynchronizationRepository->findLastSynchronization()->willReturn(null);

        self::assertEquals(
            (new \DateTimeImmutable())->setTimestamp(0),
            (new LastSynchronizationDateProvider($productSynchronizationRepository->reveal()))->provide()
        );
    }
}
