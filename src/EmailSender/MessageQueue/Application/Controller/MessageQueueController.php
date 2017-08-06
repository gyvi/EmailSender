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
        /** @var \EmailSender\MessageStore\Domain\Contract\EmailComposerInterface $emailComposer */
        $emailComposer = $this->container->get(ServiceList::EMAIL_COMPOSER);

        /** @var Closure $messageStoreReader */
        $messageStoreReader = $this->container->get(ServiceList::MESSAGE_STORE_READER);

        /** @var Closure $messageStoreWriter */
        $messageStoreWriter = $this->container->get(ServiceList::MESSAGE_STORE_WRITER);

        /** @var Closure $messageLogReader */
        $messageLogReader = $this->container->get(ServiceList::MESSAGE_LOG_READER);

        /** @var Closure $messageLogWriter */
        $messageLogWriter = $this->container->get(ServiceList::MESSAGE_LOG_WRITER);

        $messageQueueService = new MessageQueueService(
            $emailComposer,
            $messageStoreReader,
            $messageStoreWriter,
            $messageLogReader,
            $messageLogWriter
        );

        return $messageQueueService->addMessageToQueue($request, $response, $getRequest);
    }
}
