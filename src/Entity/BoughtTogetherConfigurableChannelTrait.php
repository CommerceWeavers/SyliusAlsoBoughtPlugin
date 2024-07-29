<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Entity;

use Doctrine\ORM\Mapping as ORM;

trait BoughtTogetherConfigurableChannelTrait
{
    /** @ORM\Column(name="number_of_synchronised_products", type="integer", nullable=false, options={"default": 10}) */
    #[ORM\Column(name: 'number_of_synchronised_products', type: 'integer', nullable: false, options: ['default' => 10])]
    private int $numberOfSynchronisedProducts = 10;

    public function setNumberOfSynchronisedProducts(int $number): void
    {
        $this->numberOfSynchronisedProducts = $number;
    }

    public function getNumberOfSynchronisedProducts(): int
    {
        return $this->numberOfSynchronisedProducts;
    }
}
