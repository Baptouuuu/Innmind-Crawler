<?php

namespace Innmind\CrawlerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class NormalizationCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('normalizer')) {
            return;
        }

        $def = $container->getDefinition('normalizer');

        $services = $container->findTaggedServiceIds(
            'innmind_crawler.normalization_pass'
        );

        foreach ($services as $id => $attributes) {
            $def->addMethodCall(
                'addNormalizationPass',
                [new Reference($id)]
            );
        }
    }
}
