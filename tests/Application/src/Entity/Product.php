<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Application\Entity;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\BroughtTogetherProductsAwareInterface;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\BroughtTogetherProductsAwareTrait;
use Sylius\Component\Core\Model\Product as BaseProduct;

class Product extends BaseProduct implements BroughtTogetherProductsAwareInterface
{
    use BroughtTogetherProductsAwareTrait;
}
