<?php

namespace EmailSender\EmailLog\Application\Contract;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Email\Domain\Aggregate\Email;
use EmailSender\EmailLog\Domain\Aggregate\EmailLog;
use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\MessageInterface;
use EmailSender\EmailLog\Application\ValueObject\EmailLogStatus;

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
     */
    public function add(Email $email, ComposedEmail $composedEmail): EmailLog;

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $emailLogId
     * @param \EmailSender\EmailLog\Application\ValueObject\EmailLogStatus             $emailLogStatus
     * @param null|string                                                              $errorMessage
     */
    public function setStatus(
        UnsignedInteger $emailLogId,
        EmailLogStatus $emailLogStatus,
        ?string $errorMessage
    ): void;

    /**
     * @param int $emailLogIdInt
     *
     * @return \EmailSender\EmailLog\Domain\Aggregate\EmailLog
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @throws \InvalidArgumentException
     */
    public function get(int $emailLogIdInt): EmailLog;

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $getRequest
     *
     * @return \Psr\Http\Message\MessageInterface
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
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
