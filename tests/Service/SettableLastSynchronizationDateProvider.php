<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Service;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Provider\LastSynchronizationDateProviderInterface;

final class SettableLastSynchronizationDateProvider implements LastSynchronizationDateProviderInterface
{
    public function setLastSynchronizationDate(\DateTimeImmutable $lastSynchronizationDate): void
    {
        file_put_contents(__DIR__.'/lastSynchronizationDate', $lastSynchronizationDate->format('Y-m-d H:i:s'));
    }

    public function provide(): \DateTimeInterface
    {
        $date = new \DateTimeImmutable((string) file_get_contents(__DIR__.'/lastSynchronizationDate'));
        unlink(__DIR__.'/lastSynchronizationDate');

        return $date;
    }
}
