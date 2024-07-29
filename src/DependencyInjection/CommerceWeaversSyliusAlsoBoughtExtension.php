<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\DependencyInjection;

use Sylius\Bundle\CoreBundle\DependencyInjection\PrependDoctrineMigrationsTrait;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class CommerceWeaversSyliusAlsoBoughtExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    use PrependDoctrineMigrationsTrait;

    private const ALIAS = 'commerce_weavers_sylius_also_bought';

    /** @psalm-suppress UnusedVariable */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));

        $loader->load('services.xml');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $config = $this->getCurrentConfiguration($container);

        $this->registerResources(
            self::ALIAS,
            'doctrine/orm',
            $config['resources'],
            $container,
        );

        $container->setParameter(
            'commerce_weavers_sylius_also_bought.number_of_products_to_associate',
            $config['number_of_products_to_associate'],
        );

        $container->setParameter(
            'commerce_weavers_sylius_also_bought.batch_size_limit',
            $config['batch_size_limit'],
        );

        $this->prependDoctrineMigrations($container);
        $this->prependDoctrineMapping($container);
    }

    protected function getMigrationsNamespace(): string
    {
        return 'CommerceWeaversSyliusAlsoBoughtMigrations';
    }

    protected function getMigrationsDirectory(): string
    {
        return '@CommerceWeaversSyliusAlsoBoughtPlugin/migrations';
    }

    protected function getNamespacesOfMigrationsExecutedBefore(): array
    {
        return [
            'Sylius\Bundle\CoreBundle\Migrations',
        ];
    }

    private function prependDoctrineMapping(ContainerBuilder $container): void
    {
        $config = array_merge(...$container->getExtensionConfig('doctrine'));

        // do not register mappings if dbal not configured.
        if (!isset($config['dbal']) || !isset($config['orm'])) {
            return;
        }

        $container->prependExtensionConfig('doctrine', [
            'orm' => [
                'mappings' => [
                    'CommerceWeaversSyliusAlsoBoughtPlugin' => [
                        'type' => 'xml',
                        'dir' => $this->getPath($container, '/config/doctrine/'),
                        'is_bundle' => false,
                        'prefix' => 'CommerceWeavers\SyliusAlsoBoughtPlugin\Entity',
                        'alias' => 'CommerceWeaversSyliusAlsoBoughtPlugin',
                    ],
                ],
            ],
        ]);
    }

    private function getPath(ContainerBuilder $container, string $path): string
    {
        /** @var array<string, array<string, string>> $metadata */
        $metadata = $container->getParameter('kernel.bundles_metadata');

        return $metadata['CommerceWeaversSyliusAlsoBoughtPlugin']['path'] . $path;
    }

    private function getCurrentConfiguration(ContainerBuilder $container): array
    {
        /** @var ConfigurationInterface $configuration */
        $configuration = $this->getConfiguration([], $container);

        $configs = $container->getExtensionConfig($this->getAlias());

        return $this->processConfiguration($configuration, $configs);
    }
}
