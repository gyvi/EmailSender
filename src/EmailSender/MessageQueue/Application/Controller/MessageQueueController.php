<?php

namespace EmailSender\MessageQueue\Application\Controller;

use EmailSender\Core\Controller\AbstractController;
use EmailSender\MessageQueue\Application\Service\MessageQueueService;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
        $messageQueueService = new MessageQueueService();

        return $messageQueueService->addMessageToQueue($request, $response, $getRequest);
    }
}
