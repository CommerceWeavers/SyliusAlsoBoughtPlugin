<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Doctrine\Query;

use Doctrine\ORM\EntityManagerInterface;

final class GetAssociationTypeCodeByAssociationIdQuery implements GetAssociationTypeCodeByAssociationIdQueryInterface
{
    public function __construct(
        private EntityManagerInterface $productAssociationManager,
        private string $productAssociationClass,
    ) {
    }

    public function get(int $associationId): ?string
    {
        $queryBuilder = $this->productAssociationManager->createQueryBuilder();

        $result = $queryBuilder
            ->select('t.code')
            ->from($this->productAssociationClass, 'pa')
            ->join('pa.type', 't')
            ->where('pa.id = :associationId')
            ->setParameter('associationId', $associationId)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $result['code'] ?? null;
    }
}
