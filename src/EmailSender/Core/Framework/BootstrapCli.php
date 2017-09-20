<?php

namespace EmailSender\Core\Framework;

use EmailSender\Core\Services\MessageStoreReaderService;
use EmailSender\Core\Services\MessageStoreWriterService;
use EmailSender\Core\Services\MessageLogReaderService;
use EmailSender\Core\Services\MessageLogWriterService;
use EmailSender\Core\Services\QueueService;
use EmailSender\Core\Services\SMTPService;
use Interop\Container\ContainerInterface;
use EmailSender\Core\Services\ServiceProvider;
use EmailSender\Core\Services\ServiceList;
use EmailSender\Core\Services\LoggerService;
use EmailSender\Core\Services\ErrorHandler;
use EmailSender\Core\Services\PhpErrorHandler;
use EmailSender\Core\Services\ViewService;

/**
 * Class BootstrapCli
 *
 * @package EmailSender\Core\Framework
 */
class BootstrapCli
{
    /**
     * @var \Interop\Container\ContainerInterface
     */
    private $container;

    /**
     * Bootstrap constructor.
     *
     * @param \Interop\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Bootstrap init.
     */
    public function init(): void
    {
        $this->initServices();
    }

    /**
     * Init services.
     */
    private function initServices(): void
    {
        $serviceProvider = new ServiceProvider($this->container);

        $serviceProvider->addService(ServiceList::LOGGER, new LoggerService());
        $serviceProvider->addService(ServiceList::ERROR_HANDLER, new ErrorHandler());
        $serviceProvider->addService(ServiceList::PHP_ERROR_HANDLER, new PhpErrorHandler());
        $serviceProvider->addService(ServiceList::MESSAGE_STORE_READER, new MessageStoreReaderService());
        $serviceProvider->addService(ServiceList::MESSAGE_STORE_WRITER, new MessageStoreWriterService());
        $serviceProvider->addService(ServiceList::MESSAGE_LOG_READER, new MessageLogReaderService());
        $serviceProvider->addService(ServiceList::MESSAGE_LOG_WRITER, new MessageLogWriterService());
        $serviceProvider->addService(ServiceList::QUEUE, new QueueService());
        $serviceProvider->addService(ServiceList::SMTP, new SMTPService());
        $serviceProvider->addService(ServiceList::VIEW, new ViewService());

        $serviceProvider->init();
    }
}
