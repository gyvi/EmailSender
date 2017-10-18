<?php

namespace EmailSender\Message\Domain\Builder;

use EmailSender\Core\Scalar\Application\Exception\ValueObjectException;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MailAddress\Application\Collection\MailAddressCollection;
use EmailSender\MailAddress\Application\Contract\MailAddressServiceInterface;
use EmailSender\MailAddress\Domain\Aggregate\MailAddress;
use EmailSender\Message\Application\Catalog\MessagePropertyNames;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\Core\ValueObject\Subject;
use EmailSender\Message\Domain\ValueObject\Body;
use InvalidArgumentException;

/**
 * Class MessageBuilder
 *
 * @package EmailSender\Message\Domain\Builder
 */
class MessageBuilder
{
    /**
     * @var \EmailSender\MailAddress\Application\Contract\MailAddressServiceInterface
     */
    private $mailAddressService;

    /**
     * MessageBuilder constructor.
     *
     * @param \EmailSender\MailAddress\Application\Contract\MailAddressServiceInterface $mailAddressService
     */
    public function __construct(MailAddressServiceInterface $mailAddressService)
    {
        $this->mailAddressService = $mailAddressService;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\Message\Domain\Aggregate\Message
     * @throws \InvalidArgumentException
     */
    public function buildMessageFromRequest(array $request): Message
    {
        $from    = $this->getFromFromRequest($request);
        $to      = $this->getToFromRequest($request);
        $cc      = $this->getCcFromRequest($request);
        $bcc     = $this->getBccFromRequest($request);
        $subject = $this->getSubjectFromRequest($request);
        $body    = $this->getBodyFromRequest($request);
        $replyTo = $this->getReplyToFromRequest($request);
        $delay   = $this->getDelayFromRequest($request);

        return new Message($from, $to, $cc, $bcc, $subject, $body, $replyTo, $delay);
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\MailAddress\Domain\Aggregate\MailAddress
     * @throws \InvalidArgumentException
     */
    private function getFromFromRequest(array $request): MailAddress
    {
        try {
            $from = $this->mailAddressService->getMailAddress($request[MessagePropertyNames::FROM]);
        } catch (ValueObjectException $e) {
            throw new InvalidArgumentException("Wrong property: '"  . MessagePropertyNames::FROM . "'", 0, $e);
        }

        return $from;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     * @throws \InvalidArgumentException
     */
    private function getToFromRequest(array $request): MailAddressCollection
    {
        try {
            $to = $this->mailAddressService->getMailAddressCollectionFromRequest($request[MessagePropertyNames::TO]);
        } catch (ValueObjectException $e) {
            throw new InvalidArgumentException("Wrong property: '"  . MessagePropertyNames::TO . "'", 0, $e);
        }

        if ($to->isEmpty()) {
            throw new InvalidArgumentException(
                "Wrong property: '"  . MessagePropertyNames::TO
                . "' Property requires minimum 1 valid email address."
            );
        }

        return $to;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     * @throws \InvalidArgumentException
     */
    private function getCcFromRequest(array $request): MailAddressCollection
    {
        try {
            $cc = $this->mailAddressService->getMailAddressCollectionFromRequest(
                $request[MessagePropertyNames::CC] ?? ''
            );
        } catch (ValueObjectException $e) {
            throw new InvalidArgumentException("Wrong property: '"  . MessagePropertyNames::CC . "'", 0, $e);
        }

        return $cc;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     * @throws \InvalidArgumentException
     */
    private function getBccFromRequest(array $request): MailAddressCollection
    {
        try {
            $bcc = $this->mailAddressService->getMailAddressCollectionFromRequest(
                $request[MessagePropertyNames::BCC] ?? ''
            );
        } catch (ValueObjectException $e) {
            throw new InvalidArgumentException("Wrong property: '"  . MessagePropertyNames::BCC . "'", 0, $e);
        }

        return $bcc;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\Core\ValueObject\Subject
     * @throws \InvalidArgumentException
     */
    private function getSubjectFromRequest(array $request): Subject
    {
        try {
            $subject = new Subject($request[MessagePropertyNames::SUBJECT]);
        } catch (ValueObjectException $e) {
            throw new InvalidArgumentException("Wrong property: '"  . MessagePropertyNames::SUBJECT . "'", 0, $e);
        }

        return $subject;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\Message\Domain\ValueObject\Body
     * @throws \InvalidArgumentException
     */
    private function getBodyFromRequest(array $request): Body
    {
        try {
            $body = new Body($request[MessagePropertyNames::BODY]);
        } catch (ValueObjectException $e) {
            throw new InvalidArgumentException("Wrong property: '"  . MessagePropertyNames::BODY . "'", 0, $e);
        }

        return $body;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\MailAddress\Domain\Aggregate\MailAddress|null
     * @throws \InvalidArgumentException
     */
    private function getReplyToFromRequest(array $request): ?MailAddress
    {
        $replyTo = null;

        try {
            if (isset($request[MessagePropertyNames::REPLY_TO])
                && !empty(trim($request[MessagePropertyNames::REPLY_TO]))
            ) {
                $replyTo = $this->mailAddressService->getMailAddress($request[MessagePropertyNames::REPLY_TO]);
            }
        } catch (ValueObjectException $e) {
            throw new InvalidArgumentException("Wrong property: '"  . MessagePropertyNames::REPLY_TO . "'", 0, $e);
        }

        return $replyTo;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     * @throws \InvalidArgumentException
     */
    private function getDelayFromRequest(array $request): UnsignedInteger
    {
        try {
            $delay = new UnsignedInteger(
                isset($request[MessagePropertyNames::DELAY]) ? (int)$request[MessagePropertyNames::DELAY] : 0
            );
        } catch (ValueObjectException $e) {
            throw new InvalidArgumentException("Wrong property: '" . MessagePropertyNames::DELAY . "'", 0, $e);
        }

        return $delay;
    }
}
