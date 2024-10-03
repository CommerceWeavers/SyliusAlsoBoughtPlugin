<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Behat\Element\Admin;

use FriendsOfBehat\PageObjectExtension\Element\Element;

final class ChannelFormElement extends Element implements ChannelFormElementInterface
{
    public function changeNumberOfSynchronisedProducts(int $number): void
    {
        $this->getElement('number_of_synchronised_products_input')->setValue((string) $number);
    }

    public function getNumberOfSynchronisedProducts(): int
    {
        return (int) $this->getElement('number_of_synchronised_products_input')->getValue();
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'number_of_synchronised_products_input' => 'input[name="sylius_channel[numberOfSynchronisedProducts]"]',
        ]);
    }
}
