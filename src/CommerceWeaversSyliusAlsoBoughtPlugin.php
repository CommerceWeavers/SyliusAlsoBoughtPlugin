<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class CommerceWeaversSyliusAlsoBoughtPlugin extends Bundle
{
    use SyliusPluginTrait;

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
