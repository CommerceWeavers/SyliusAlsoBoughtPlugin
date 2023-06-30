<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Doctrine\ORM;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\ProductSynchronizationInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Webmozart\Assert\Assert;

final class ProductSynchronizationRepository extends EntityRepository implements ProductSynchronizationRepositoryInterface
{
    public function findLastSynchronization(): ?ProductSynchronizationInterface
    {
        $productSynchronization = $this->createQueryBuilder('o')
            ->andWhere('o.endDate IS NOT NULL')
            ->orderBy('o.endDate', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        Assert::nullOrIsInstanceOf($productSynchronization, ProductSynchronizationInterface::class);

        return $productSynchronization;
    }
}
