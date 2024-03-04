<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Unit\DependencyInjection;

use CommerceWeavers\SyliusAlsoBoughtPlugin\DependencyInjection\Configuration;
use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;

final class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    /** @test */
    public function it_defines_number_of_products_to_associate_by_default(): void
    {
        $this->assertProcessedConfigurationEquals(
            [],
            ['number_of_products_to_associate' => 10],
            'number_of_products_to_associate'
        );
    }

    /** @test */
    public function it_allows_to_define_number_of_products_to_associate(): void
    {
        $this->assertProcessedConfigurationEquals(
            [['number_of_products_to_associate' => 5]],
            ['number_of_products_to_associate' => 5],
            'number_of_products_to_associate'
        );
    }

    protected function getConfiguration(): Configuration
    {
        return new Configuration();
    }
}
