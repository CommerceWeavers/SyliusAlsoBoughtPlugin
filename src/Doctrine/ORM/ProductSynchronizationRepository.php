<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Doctrine\ORM;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\ProductSynchronizationInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class ProductSynchronizationRepository extends EntityRepository implements ProductSynchronizationRepositoryInterface
{
    public function findLastSynchronization(): ?ProductSynchronizationInterface
    {
        /** @var ProductSynchronizationInterface|null $productSynchronization */
        $productSynchronization = $this->createQueryBuilder('o')
            ->andWhere('o.endDate IS NOT NULL')
            ->orderBy('o.endDate', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $productSynchronization;
    }
}
