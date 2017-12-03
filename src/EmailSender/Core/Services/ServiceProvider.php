<?php

namespace EmailSender\Core\Services;

use Interop\Container\ContainerInterface;

/**
 * Class ServiceProvider
 *
 * @package EmailSender\Core
 */
class ServiceProvider
{
    /**
     * @var \Interop\Container\ContainerInterface
     */
    private $container;

    /**
     * @var \EmailSender\Core\Services\ServiceInterface[]
     */
    private $services;

    /**
     * ServiceProvider constructor.
     *
     * @param \Interop\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Init services.
     */
    public function init(): void
    {
        foreach ($this->services as $serviceName => $service) {
            $this->container[$serviceName] = $service->getService();
        }
    }

    /**
     * @param string                                      $serviceName
     * @param \EmailSender\Core\Services\ServiceInterface $service
     */
    public function addService(string $serviceName, ServiceInterface $service): void
    {
        $this->services[$serviceName] = $service;
    }
}
