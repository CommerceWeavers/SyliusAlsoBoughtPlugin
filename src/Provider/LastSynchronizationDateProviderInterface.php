<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Provider;

interface LastSynchronizationDateProviderInterface
{
    public function provide(): \DateTimeInterface;
}
