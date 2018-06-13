<?php
/**
 * Created by PhpStorm.
 * User: bdunogier
 * Date: 13/06/2018
 * Time: 13:12
 */

namespace BD\EzPlatformFileBasedContentType\Bridge\Symfony\Bundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection;

class RegisterContentTypesClassesPass implements DependencyInjection\Compiler\CompilerPassInterface
{
    const TAG = 'ezplatform.content_type_provider';
    const HANDLER_SERVICE_ID = 'BD\EzPlatformFileBasedContentType\Platform\SPI\Persistence\ContentTypeHandler';

    public function process(DependencyInjection\ContainerBuilder $container)
    {
        if (!$container->hasDefinition(self::HANDLER_SERVICE_ID)) {
            return;
        }

        $contentTypeProviders = [];
        foreach ($container->findTaggedServiceIds(self::TAG) as $taggedServiceId => $tags) {
            foreach ($tags as $tag) {
                $contentTypeProviders[] = new DependencyInjection\Reference($taggedServiceId);
            }
        }

        $handlerDefinition = $container->getDefinition(self::HANDLER_SERVICE_ID);
        $handlerDefinition->setArgument('$typesProviders', $contentTypeProviders);
    }
}