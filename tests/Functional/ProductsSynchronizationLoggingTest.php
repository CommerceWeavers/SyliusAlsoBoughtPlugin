<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Functional;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\ProductSynchronization;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Event\SynchronizationEnded;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Event\SynchronizationStarted;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

final class ProductsSynchronizationLoggingTest extends KernelTestCase
{
    private MessageBusInterface $eventBus;

    protected function setUp(): void
    {
        $this->eventBus = parent::getContainer()->get('sylius.event_bus');
    }

    public function testLoggingFrequentlyBoughtProductsSynchronization(): void
    {
        $id = Uuid::v4();
        $startDate = new \DateTimeImmutable('2021-01-01 08:02:11');
        $endDate = new \DateTimeImmutable('2021-01-01 10:00:10');

        $this->eventBus->dispatch(new SynchronizationStarted($id, $startDate));
        $x = new SynchronizationEnded($id, $endDate, 10, ['product1', 'product2']);
        $this->eventBus->dispatch($x);

        /** @var EntityManagerInterface $entityManager */
        $entityManager = parent::getContainer()->get('doctrine.orm.entity_manager');
        $productSynchronizationRepository = $entityManager->getRepository(ProductSynchronization::class);

        self::assertCount(1, $productSynchronizationRepository->findAll());
        /** @var ProductSynchronization $productSynchronizationLog */
        $productSynchronizationLog = $productSynchronizationRepository->findOneBy(['id' => $id]);

        self::assertSame($startDate, $productSynchronizationLog->getStartDate());
        self::assertSame($endDate, $productSynchronizationLog->getEndDate());
        self::assertSame(10, $productSynchronizationLog->getNumberOfOrders());
        self::assertSame(['product1', 'product2'], $productSynchronizationLog->getAffectedProducts());
    }

    protected function tearDown(): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = parent::getContainer()->get('doctrine.orm.entity_manager');
        $productSynchronizationRepository = $entityManager->getRepository(ProductSynchronization::class);
        foreach ($productSynchronizationRepository->findAll() as $productSynchronization) {
            $entityManager->remove($productSynchronization);
        }
        $entityManager->flush();

        parent::tearDown();
    }
}
