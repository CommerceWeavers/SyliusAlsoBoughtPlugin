<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * @psalm-suppress UnusedVariable
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('commerce_weavers_sylius_also_bought_plugin');
        $rootNode = $treeBuilder->getRootNode();

        return $treeBuilder;
    }
}
