<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Synchronizer;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Model\SynchronizationResult;

interface FrequentlyBoughtTogetherProductsSynchronizerInterface
{
    public function synchronize(\DateTimeInterface $since): SynchronizationResult;
}
