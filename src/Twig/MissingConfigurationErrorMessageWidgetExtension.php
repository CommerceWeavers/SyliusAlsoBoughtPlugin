<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Twig;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Checker\BoughtTogetherProductAssociationTypeConfiguredCheckerInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class MissingConfigurationErrorMessageWidgetExtension extends AbstractExtension
{
    public function __construct(
        private BoughtTogetherProductAssociationTypeConfiguredCheckerInterface $boughtTogetherProductAssociationTypeConfiguredChecker,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'commerce_weavers_sylius_also_bought_missing_configuration_error_message_widget',
                [$this, 'render'],
                [
                    'needs_environment' => true,
                    'is_safe' => ['html'],
                ],
            ),
        ];
    }

    public function render(Environment $environment): string
    {
        if ($this->boughtTogetherProductAssociationTypeConfiguredChecker->isConfigured()) {
            return '';
        }

        return $environment->render('@CommerceWeaversSyliusAlsoBoughtPlugin/_error_message_widget.html.twig');
    }
}
