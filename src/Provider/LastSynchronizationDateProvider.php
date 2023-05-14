<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Provider;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\ProductSynchronization;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class LastSynchronizationDateProvider implements LastSynchronizationDateProviderInterface
{
    /**
     * @param RepositoryInterface<ProductSynchronization> $productSynchronizationRepository
     */
    public function __construct(private RepositoryInterface $productSynchronizationRepository)
    {
    }

    public function provide(): \DateTimeInterface
    {
        $lastSynchronization = $this->productSynchronizationRepository->findBy([], ['endDate' => 'DESC']);

        if (isset($lastSynchronization[0])) {
            /** @var \DateTimeInterface $endDate */
            $endDate = $lastSynchronization[0]->getEndDate();

            return $endDate;
        }

        return (new \DateTimeImmutable())->setTimestamp(0);
    }
}
