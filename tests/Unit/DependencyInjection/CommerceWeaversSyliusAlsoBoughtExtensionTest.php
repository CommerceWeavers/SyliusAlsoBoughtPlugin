<?php

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Unit\DependencyInjection;

use CommerceWeavers\SyliusAlsoBoughtPlugin\DependencyInjection\CommerceWeaversSyliusAlsoBoughtExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

final class CommerceWeaversSyliusAlsoBoughtExtensionTest extends AbstractExtensionTestCase
{
    /** @test */
    public function it_prepends_configuration_with_number_of_products_to_associate_with_default_value(): void
    {
        $this->prepend();

        $this->assertContainerBuilderHasParameter(
            'commerce_weavers_sylius_also_bought_plugin.number_of_products_to_associate',
            10,
        );
    }

    /** @test */
    public function it_prepends_configuration_with_number_of_products_to_associate_with_given_value(): void
    {
        $this->container->prependExtensionConfig(
            'commerce_weavers_sylius_also_bought',
            ['number_of_products_to_associate' => 5],
        );

        $this->prepend();

        $this->assertContainerBuilderHasParameter(
            'commerce_weavers_sylius_also_bought_plugin.number_of_products_to_associate',
            5,
        );
    }

    protected function getContainerExtensions(): array
    {
        return [new CommerceWeaversSyliusAlsoBoughtExtension()];
    }

    private function prepend(): void
    {
        foreach ($this->container->getExtensions() as $extension) {
            if ($extension instanceof PrependExtensionInterface) {
                $extension->prepend($this->container);
            }
        }
    }
}
