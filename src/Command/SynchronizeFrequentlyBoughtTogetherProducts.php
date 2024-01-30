<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Command;

final class SynchronizeFrequentlyBoughtTogetherProducts
{
    private \DateTimeInterface $synchronizedAt;

    public function __construct()
    {
        $this->synchronizedAt = new \DateTimeImmutable();
    }

    public function getSynchronizedAt(): \DateTimeInterface
    {
        return $this->synchronizedAt;
    }
}
