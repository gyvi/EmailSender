<?php

namespace EmailSender\MessageQueue\Application\Contract;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\MessageInterface;

/**
 * Interface MessageQueueServiceInterface
 *
 * @package EmailSender\MessageQueue
 */
interface MessageQueueServiceInterface
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $getRequest
     *
     * @return \Psr\Http\Message\MessageInterface
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function addMessageToQueue(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $getRequest
    ): MessageInterface;

    /**
     * @param string $messageQueue
     *
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @throws \EmailSender\MessageQueue\Infrastructure\Service\SMTPException
     * @throws \InvalidArgumentException
     */
    public function sendMessageFromQueue(string $messageQueue): void;
}
