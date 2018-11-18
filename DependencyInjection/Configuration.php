<?php

namespace DoctrineEnumMigration\DependencyInjection;

use DoctrineEnumMigration\Command\UpdateSchemaCommand;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('doctrine_enum_migration');

        $rootNode
            ->children()
                ->scalarNode('command_class')
                    ->defaultValue(UpdateSchemaCommand::class)
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
