<?php

namespace EmailSender\Core\Services;

use Interop\Container\ContainerInterface;
use Closure;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

/**
 * Class TwigService
 *
 * @package EmailSender\Core
 */
class ViewService implements ServiceInterface
{
    /**
     * @return \Closure
     */
    public function getService(): Closure
    {
        return function (ContainerInterface $container): Closure {
            return function () use ($container) {
                $view = new Twig(dirname(__DIR__, 2));

                /** @var \Slim\Http\Request $request */
                $request = $container->get('request');

                /** @var \Slim\Http\Uri $uri */
                $uri = $request->getUri();

                $basePath = rtrim(str_ireplace('index.php', '', $uri->getBasePath()), '/');

                $view->addExtension(new TwigExtension($container->get('router'), $basePath));

                return $view;
            };
        };
    }
}
