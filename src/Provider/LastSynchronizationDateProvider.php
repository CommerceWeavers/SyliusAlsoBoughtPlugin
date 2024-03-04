<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Provider;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Doctrine\ORM\ProductSynchronizationRepositoryInterface;
use Webmozart\Assert\Assert;

final class LastSynchronizationDateProvider implements LastSynchronizationDateProviderInterface
{
    public function __construct(private ProductSynchronizationRepositoryInterface $productSynchronizationRepository)
    {
    }

    public function provide(): ?\DateTimeInterface
    {
        $lastSynchronization = $this->productSynchronizationRepository->findLastSynchronization();

        if (null === $lastSynchronization) {
            return null;
        }

        $endDate = $lastSynchronization->getEndDate();
        Assert::notNull($endDate);

        return $endDate;
    }
}
