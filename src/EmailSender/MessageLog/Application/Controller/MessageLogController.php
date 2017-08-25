<?php

namespace EmailSender\MessageLog\Application\Controller;

use EmailSender\Core\Controller\AbstractController;
use EmailSender\MessageLog\Application\Service\MessageLogService;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use EmailSender\Core\Services\ServiceList;
use Closure;

/**
 * Class MessageLogController
 *
 * @package EmailSender\MessageLog
 */
class MessageLogController extends AbstractController
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
    public function listMessageLogs(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $getRequest
    ): MessageInterface {

        /** @var \Psr\Log\LoggerInterface $logger */
        $logger = $this->container->get(ServiceList::LOGGER);

        /** @var Closure $messageLogReader */
        $messageLogReader = $this->container->get(ServiceList::MESSAGE_LOG_READER);

        /** @var Closure $messageLogWriter */
        $messageLogWriter = $this->container->get(ServiceList::MESSAGE_LOG_WRITER);

        $messageQueueService = new MessageLogService(
            $logger,
            $messageLogReader,
            $messageLogWriter
        );

        return $messageQueueService->listMessageLogs($request, $response, $getRequest);
    }
}
