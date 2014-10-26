<?php

namespace Innmind\CrawlerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Innmind\CrawlerBundle\DependencyInjection\Compiler\NormalizationCompilerPass;

class InnmindCrawlerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new NormalizationCompilerPass);
    }
}
