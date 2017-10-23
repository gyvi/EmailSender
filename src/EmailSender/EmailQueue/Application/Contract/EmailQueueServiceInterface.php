<?php

namespace EmailSender\EmailQueue\Application\Contract;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\MessageInterface;

/**
 * Interface EmailQueueServiceInterface
 *
 * @package EmailSender\EmailQueue
 */
interface EmailQueueServiceInterface
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
    public function add(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $getRequest
    ): MessageInterface;

    /**
     * @param string $emailQueue
     *
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @throws \EmailSender\EmailQueue\Infrastructure\Service\SMTPException
     * @throws \InvalidArgumentException
     */
    public function sendEmailFromQueue(string $emailQueue): void;
}
