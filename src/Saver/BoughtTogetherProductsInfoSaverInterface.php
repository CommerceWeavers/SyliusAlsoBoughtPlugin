<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Saver;

interface BoughtTogetherProductsInfoSaverInterface
{
    /** @param string[] $productCodes */
    public function save(string $productCode, array $productCodes): void;
}
