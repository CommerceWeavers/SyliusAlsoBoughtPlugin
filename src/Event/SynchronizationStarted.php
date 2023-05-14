<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Event;

use Symfony\Component\Uid\Uuid;

final class SynchronizationStarted
{
    public function __construct(
        private Uuid $id,
        private \DateTimeImmutable $date,
    ) {
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function date(): \DateTimeImmutable
    {
        return $this->date;
    }
}
