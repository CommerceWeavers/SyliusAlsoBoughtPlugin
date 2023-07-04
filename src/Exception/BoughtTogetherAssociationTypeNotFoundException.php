<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Exception;

class BoughtTogetherAssociationTypeNotFoundException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Bought together association type not found.');
    }
}
