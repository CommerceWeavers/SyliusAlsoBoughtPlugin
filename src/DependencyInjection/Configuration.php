<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\DependencyInjection;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Doctrine\ORM\ProductSynchronizationRepository;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\ProductSynchronization;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\ProductSynchronizationInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('commerce_weavers_sylius_also_bought');
        $rootNode = $treeBuilder->getRootNode();

        $this->addConfiguration($rootNode);
        $this->addResourcesSection($rootNode);

        return $treeBuilder;
    }

    private function addConfiguration(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->integerNode('number_of_products_to_associate')->defaultValue(10)->end()
            ->end()
        ;
    }

    private function addResourcesSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('product_synchronization')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(ProductSynchronization::class)->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(ProductSynchronizationInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(ProductSynchronizationRepository::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
