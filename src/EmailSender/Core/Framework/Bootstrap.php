<?php

namespace EmailSender\Core\Framework;

use EmailSender\Core\Services\ComposedReaderService;
use EmailSender\Core\Services\ComposedEmailWriterService;
use EmailSender\Core\Services\EmailLogReaderService;
use EmailSender\Core\Services\EmailLogWriterService;
use EmailSender\Core\Services\QueueService;
use EmailSender\Core\Services\ViewService;
use Interop\Container\ContainerInterface;
use Slim\App;
use EmailSender\Core\Services\ServiceProvider;
use EmailSender\Core\Services\ServiceList;
use EmailSender\Core\Services\LoggerService;
use EmailSender\Core\Services\ErrorHandler;
use EmailSender\Core\Services\PhpErrorHandler;
use EmailSender\Core\Route\Routing;
use EmailSender\EmailLog\Application\Route\Route as EmailLogRoute;
use EmailSender\EmailQueue\Application\Route\Route as EmailQueueRoute;
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

        $serviceProvider->addService(ServiceList::LOGGER, new LoggerService());
        $serviceProvider->addService(ServiceList::ERROR_HANDLER, new ErrorHandler());
        $serviceProvider->addService(ServiceList::PHP_ERROR_HANDLER, new PhpErrorHandler());
        $serviceProvider->addService(ServiceList::COMPOSED_EMAIL_READER, new ComposedReaderService());
        $serviceProvider->addService(ServiceList::COMPOSED_EMAIL_WRITER, new ComposedEmailWriterService());
        $serviceProvider->addService(ServiceList::EMAIL_LOG_READER, new EmailLogReaderService());
        $serviceProvider->addService(ServiceList::EMAIL_LOG_WRITER, new EmailLogWriterService());
        $serviceProvider->addService(ServiceList::QUEUE, new QueueService());
        $serviceProvider->addService(ServiceList::SMTP, new SMTPService());
        $serviceProvider->addService(ServiceList::VIEW, new ViewService());

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

        $routing->add(new EmailLogRoute($app));
        $routing->add(new EmailQueueRoute($app));

        $routing->init();
    }
}
