<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Doctrine\Query;

interface GetAssociationTypeCodeByAssociationIdQueryInterface
{
    public function get(int $associationId): ?string;
}
