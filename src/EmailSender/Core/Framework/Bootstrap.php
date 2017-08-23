<?php

namespace EmailSender\Core\Framework;

use EmailSender\Core\Services\MessageStoreReaderService;
use EmailSender\Core\Services\MessageStoreWriterService;
use EmailSender\Core\Services\MessageLogReaderService;
use EmailSender\Core\Services\MessageLogWriterService;
use EmailSender\Core\Services\QueueService;
use Interop\Container\ContainerInterface;
use Slim\App;
use EmailSender\Core\Services\ServiceProvider;
use EmailSender\Core\Services\ServiceList;
use EmailSender\Core\Services\LoggerService;
use EmailSender\Core\Services\ErrorHandler;
use EmailSender\Core\Services\PhpErrorHandler;
use EmailSender\Core\Route\Routing;
use EmailSender\MessageLog\Application\Route\Route as MessageLogRoute;
use EmailSender\MessageQueue\Application\Route\Route as MessageQueueRoute;
use EmailSender\Core\Services\SMTPService;

/**
 * Class Bootstrap
 *
 * @package EmailSender\Core
 */
class Bootstrap
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
     *
     * @return App
     */
    public function init(): App
    {
        $this->initServices();

        $app = new App($this->container);

        $this->initRouting($app);

        return $app;
    }

    /**
     * Init services.
     */
    private function initServices(): void
    {
        $serviceProvider = new ServiceProvider($this->container);

        $serviceProvider->addService(ServiceList::LOGGER,               new LoggerService());
        $serviceProvider->addService(ServiceList::ERROR_HANDLER,        new ErrorHandler());
        $serviceProvider->addService(ServiceList::PHP_ERROR_HANDLER,    new PhpErrorHandler());
        $serviceProvider->addService(ServiceList::MESSAGE_STORE_READER, new MessageStoreReaderService());
        $serviceProvider->addService(ServiceList::MESSAGE_STORE_WRITER, new MessageStoreWriterService());
        $serviceProvider->addService(ServiceList::MESSAGE_LOG_READER,   new MessageLogReaderService());
        $serviceProvider->addService(ServiceList::MESSAGE_LOG_WRITER,   new MessageLogWriterService());
        $serviceProvider->addService(ServiceList::QUEUE,                new QueueService());
        $serviceProvider->addService(ServiceList::SMTP,                 new SMTPService());

        $serviceProvider->init();
    }

    /**
     * Init routing.
     *
     * @param \Slim\App $app
     */
    private function initRouting(App $app): void
    {
        $routing   = new Routing();

        $routing->add(new MessageLogRoute($app));
        $routing->add(new MessageQueueRoute($app));

        $routing->init();
    }
}
