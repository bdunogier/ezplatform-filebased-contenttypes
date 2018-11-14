<?php
namespace BD\EzPlatformFileBasedContentType\Bridge\Symfony\Bundle;

use BD\EzPlatformFileBasedContentType\Bridge\Symfony\Bundle\DependencyInjection\Compiler\RegisterContentTypesClassesPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel;

class EzPlatformFileBasedContentTypesBundle extends HttpKernel\Bundle\Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterContentTypesClassesPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, -100);
    }
}
