<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Checker;

interface BoughtTogetherProductAssociationTypeConfiguredCheckerInterface
{
    public function isConfigured(): bool;
}
