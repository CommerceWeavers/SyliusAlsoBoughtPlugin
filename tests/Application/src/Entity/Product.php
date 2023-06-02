<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Application\Entity;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\BroughtTogetherProductsAwareInterface;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\BroughtTogetherProductsAwareTrait;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\Product as BaseProduct;

#[ORM\Entity]
#[ORM\Table(name: 'sylius_product')]
class Product extends BaseProduct implements BroughtTogetherProductsAwareInterface
{
    use BroughtTogetherProductsAwareTrait;
}
