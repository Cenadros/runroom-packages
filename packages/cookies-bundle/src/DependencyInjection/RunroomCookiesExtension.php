<?php

declare(strict_types=1);

/*
 * This file is part of the Runroom package.
 *
 * (c) Runroom <runroom@runroom.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Runroom\CookiesBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class RunroomCookiesExtension extends Extension
{
    /**
     * @psalm-suppress UndefinedInterfaceMethod $bundles is an array
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $bundles = $container->getParameter('kernel.bundles');

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.php');

        if (isset($bundles['SonataAdminBundle'], $bundles['FOSCKEditorBundle'], $bundles['A2lixTranslationFormBundle'])) {
            $loader->load('admin.php');
        }

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition('runroom.cookies.twig.cookies_runtime');
        $definition->setArgument('$cookies', $config['cookies']);

        $definition = $container->getDefinition('runroom.cookies.service.cookies_page');
        $definition->setArgument('$cookies', $config['cookies']);
    }
}
