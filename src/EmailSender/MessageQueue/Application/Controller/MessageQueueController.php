<?php

namespace EmailSender\MessageQueue\Application\Controller;

use EmailSender\Core\Controller\AbstractController;
use EmailSender\Core\Services\ServiceList;
use EmailSender\MessageQueue\Application\Service\MessageQueueService;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Closure;

/**
 * Class MessageQueueController
 *
 * @package EmailSender\MessageQueue\Application\Controller
 */
class MessageQueueController extends AbstractController
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $getRequest
     *
     * @return \Psr\Http\Message\MessageInterface
     *
     * @ignoreCodeCoverage
     */
    public function addMessageToQueue(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $getRequest
    ): MessageInterface {
        /** @var Closure $view */
        $view = $this->container->get(ServiceList::VIEW);

        /** @var \Psr\Log\LoggerInterface $logger */
        $logger = $this->container->get(ServiceList::LOGGER);

        /** @var Closure $messageStoreReader */
        $messageStoreReader = $this->container->get(ServiceList::MESSAGE_STORE_READER);

        /** @var Closure $messageStoreWriter */
        $messageStoreWriter = $this->container->get(ServiceList::MESSAGE_STORE_WRITER);

        /** @var Closure $messageLogReader */
        $messageLogReader = $this->container->get(ServiceList::MESSAGE_LOG_READER);

        /** @var Closure $messageLogWriter */
        $messageLogWriter = $this->container->get(ServiceList::MESSAGE_LOG_WRITER);

        /** @var Closure $queueService */
        $queueService = $this->container->get(ServiceList::QUEUE);

        /** @var array $queueServiceSettings */
        $queueServiceSettings = $this->container->get('settings')[ServiceList::QUEUE];

        /** @var array $queueServiceSettings */
        $smtpService = $this->container->get(ServiceList::SMTP);

        $messageQueueService = new MessageQueueService(
            $view,
            $logger,
            $queueService,
            $queueServiceSettings,
            $messageStoreReader,
            $messageStoreWriter,
            $messageLogReader,
            $messageLogWriter,
            $smtpService
        );

        return $messageQueueService->addMessageToQueue($request, $response, $getRequest);
    }
}
