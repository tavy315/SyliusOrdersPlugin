<?php

declare(strict_types=1);

namespace Tavy315\SyliusOrdersPlugin\DependencyInjection;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Tavy315\SyliusOrdersPlugin\Entity\CustomerOrder;
use Tavy315\SyliusOrdersPlugin\Entity\CustomerOrderInterface;
use Tavy315\SyliusOrdersPlugin\Form\Type\CustomerOrderType;
use Tavy315\SyliusOrdersPlugin\Repository\CustomerOrderRepository;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('tavy315_sylius_orders');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('driver')->defaultValue(SyliusResourceBundle::DRIVER_DOCTRINE_ORM)->end()
            ->end();
        $rootNode
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('customer_order')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->cannotBeEmpty()->end()
                                        ->scalarNode('form')->defaultValue(CustomerOrderType::class)->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(CustomerOrderInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('model')->defaultValue(CustomerOrder::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(CustomerOrderRepository::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
