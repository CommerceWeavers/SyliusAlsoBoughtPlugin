<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Exception;

final class ProductAssociationTypeNotFoundException extends \RuntimeException
{
    public function __construct(int $associationId)
    {
        parent::__construct(sprintf('Product association type not found for association with id "%d".', $associationId));
    }
}
