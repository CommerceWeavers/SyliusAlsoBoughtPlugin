<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Product\Model\ProductAssociationInterface;

trait BroughtTogetherProductsAwareTrait
{
    /**
     * @var array<string, int>
     *
     * @ORM\Column(name="bought_together_products", type="json")
     */
    #[ORM\Column(name: 'bought_together_products', type: 'json')]
    private array $boughtTogetherProducts = [];

    public function getBoughtTogetherProducts(): array
    {
        return $this->boughtTogetherProducts;
    }

    /**
     * @param string[] $codes
     */
    public function increaseBoughtTogetherProductsCount(array $codes): void
    {
        foreach ($codes as $code) {
            if (!isset($this->boughtTogetherProducts[$code])) {
                $this->boughtTogetherProducts[$code] = 1;
                continue;
            }
            ++$this->boughtTogetherProducts[$code];
        }

        arsort($this->boughtTogetherProducts);
    }

    public function getBroughtTogetherAssociation(): ?ProductAssociationInterface
    {
        foreach ($this->associations as $association) {
            if ($association->getType()->getCode() === BroughtTogetherProductsAwareInterface::BOUGHT_TOGETHER_ASSOCIATION_TYPE_CODE) {
                return $association;
            }
        }

        return null;
    }
}
