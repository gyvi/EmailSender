<?php

namespace EmailSender\Core\Controller;

use Interop\Container\ContainerInterface;

/**
 * Class AbstractController
 *
 * @package EmailSender\Core
 */
abstract class AbstractController
{
    /**
     * @var \Interop\Container\ContainerInterface
     */
    protected $container;

    /**
     * AbstractController constructor.
     *
     * @param \Interop\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
