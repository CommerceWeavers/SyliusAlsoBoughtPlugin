<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Unit\Handler;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\ProductSynchronization;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Event\SynchronizationStarted;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Handler\SynchronizationStartedHandler;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Uid\Uuid;

final class SynchronizationStartedHandlerTest extends TestCase
{
    use ProphecyTrait;

    public function testHandlingSynchronizationStartedEvent(): void
    {
        $id = Uuid::v4();
        $startDate = new \DateTimeImmutable('2021-01-01 08:02:11');

        $entityManager = $this->prophesize(EntityManagerInterface::class);

        $entityManager->persist(Argument::that(function (ProductSynchronization $productSynchronization) use ($id, $startDate) {
            return $productSynchronization->getId() === $id && $productSynchronization->getStartDate() == $startDate;
        }))->shouldBeCalled();

        (new SynchronizationStartedHandler($entityManager->reveal()))(new SynchronizationStarted($id, $startDate));
    }
}
