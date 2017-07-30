<?php

namespace EmailSender\MessageLog\Application\Controller;

use EmailSender\Core\Controller\AbstractController;
use EmailSender\MessageLog\Application\Service\MessageLogService;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
    public function listMessagesFromLog(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $getRequest
    ): MessageInterface {
        $messageQueueService = new MessageLogService();

        return $messageQueueService->listMessagesFromLog($request, $response, $getRequest);
    }
}
