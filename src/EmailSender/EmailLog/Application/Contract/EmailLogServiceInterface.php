<?php

namespace EmailSender\EmailLog\Application\Contract;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Email\Domain\Aggregate\Email;
use EmailSender\EmailLog\Domain\Aggregate\EmailLog;
use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\MessageInterface;
use EmailSender\Core\ValueObject\EmailStatus;

/**
 * Interface EmailLogServiceInterface
 *
 * @package EmailSender\EmailLog
 */
interface EmailLogServiceInterface
{
    /**
     * @param \EmailSender\Email\Domain\Aggregate\Email               $email
     * @param \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail $composedEmail
     *
     * @return \EmailSender\EmailLog\Domain\Aggregate\EmailLog
     *
     * @throws \EmailSender\EmailLog\Application\Exception\EmailLogException
     */
    public function add(Email $email, ComposedEmail $composedEmail): EmailLog;

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $emailLogId
     * @param \EmailSender\Core\ValueObject\EmailStatus                                $emailLogStatus
     * @param null|string                                                              $errorMessage
     *
     * @throws \EmailSender\EmailLog\Application\Exception\EmailLogException
     */
    public function setStatus(
        UnsignedInteger $emailLogId,
        EmailStatus $emailLogStatus,
        ?string $errorMessage
    ): void;

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $getRequest
     *
     * @return \Psr\Http\Message\MessageInterface
     */
    public function list(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $getRequest
    ): MessageInterface;

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $getRequest
     *
     * @return \Psr\Http\Message\MessageInterface
     */
    public function lister(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $getRequest
    ): MessageInterface;
}
