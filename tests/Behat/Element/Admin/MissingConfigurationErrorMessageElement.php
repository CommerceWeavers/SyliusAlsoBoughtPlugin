<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Behat\Element\Admin;

use FriendsOfBehat\PageObjectExtension\Element\Element;

final class MissingConfigurationErrorMessageElement extends Element implements MissingConfigurationErrorMessageElementInterface
{
    public function hasErrorMessage(): bool
    {
        return $this->hasElement('error_message');
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'error_message' => '#sylius-also-bought-plugin-error',
        ]);
    }
}
