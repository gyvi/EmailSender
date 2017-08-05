<?php

namespace EmailSender\Message\Domain\Builder;

use EmailSender\Core\Scalar\Application\Exception\ValueObjectException;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MailAddress\Application\Collection\MailAddressCollection;
use EmailSender\MailAddress\Application\Service\MailAddressService;
use EmailSender\MailAddress\Domain\Aggregate\MailAddress;
use EmailSender\Message\Application\Catalog\MessagePropertyList;
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
     * @var \EmailSender\MailAddress\Application\Service\MailAddressService
     */
    private $mailAddressService;

    /**
     * MessageBuilder constructor.
     *
     * @param \EmailSender\MailAddress\Application\Service\MailAddressService $mailAddressService
     */
    public function __construct(MailAddressService $mailAddressService)
    {
        $this->mailAddressService = $mailAddressService;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\Message\Domain\Aggregate\Message
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
     */
    private function getFromFromRequest(array $request): MailAddress
    {
        try {
            $from = $this->mailAddressService->getMailAddressFromString($request[MessagePropertyList::FROM]);
        } catch (ValueObjectException $e) {

            throw new InvalidArgumentException("Wrong property: '"  . MessagePropertyList::FROM . "'", 0, $e);
        }

        return $from;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     *
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     */
    private function getToFromRequest(array $request): MailAddressCollection
    {
        try {
            $to = $this->mailAddressService->getMailAddressCollectionFromString($request[MessagePropertyList::TO]);
        } catch (ValueObjectException $e) {

            throw new InvalidArgumentException("Wrong property: '"  . MessagePropertyList::TO . "'", 0, $e);
        }

        if ($to->isEmpty()) {

            throw new InvalidArgumentException(
                "Wrong property: '"  . MessagePropertyList::TO
                . "' Property requires minimum 1 valid email address."
            );
        }

        return $to;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     */
    private function getCcFromRequest(array $request): MailAddressCollection
    {
        try {
            $cc = $this->mailAddressService->getMailAddressCollectionFromString(
                isset($request[MessagePropertyList::CC]) ? $request[MessagePropertyList::CC] : ''
            );
        } catch (ValueObjectException $e) {

            throw new InvalidArgumentException("Wrong property: '"  . MessagePropertyList::CC . "'", 0, $e);
        }

        return $cc;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     */
    private function getBccFromRequest(array $request): MailAddressCollection
    {
        try {
            $bcc = $this->mailAddressService->getMailAddressCollectionFromString(
                isset($request[MessagePropertyList::BCC]) ? $request[MessagePropertyList::BCC] : ''
            );
        } catch (ValueObjectException $e) {

            throw new InvalidArgumentException("Wrong property: '"  . MessagePropertyList::BCC . "'", 0, $e);
        }

        return $bcc;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\Core\ValueObject\Subject
     */
    private function getSubjectFromRequest(array $request): Subject
    {
        try {
            $subject = new Subject($request[MessagePropertyList::SUBJECT]);
        } catch (ValueObjectException $e) {

            throw new InvalidArgumentException("Wrong property: '"  . MessagePropertyList::SUBJECT . "'", 0, $e);
        }

        return $subject;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\Message\Domain\ValueObject\Body
     */
    private function getBodyFromRequest(array $request): Body
    {
        try {
            $body = new Body($request[MessagePropertyList::BODY]);
        } catch (ValueObjectException $e) {

            throw new InvalidArgumentException("Wrong property: '"  . MessagePropertyList::BODY . "'", 0, $e);
        }

        return $body;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\MailAddress\Domain\Aggregate\MailAddress|null
     */
    private function getReplyToFromRequest(array $request): ?MailAddress
    {
        $replyTo = null;

        try {
            if (isset($request[MessagePropertyList::REPLY_TO])
                && !empty(trim($request[MessagePropertyList::REPLY_TO]))
            ) {
                $replyTo = $this->mailAddressService->getMailAddressFromString($request[MessagePropertyList::REPLY_TO]);
            }
        } catch (ValueObjectException $e) {

            throw new InvalidArgumentException("Wrong property: '"  . MessagePropertyList::REPLY_TO . "'", 0, $e);
        }

        return $replyTo;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     */
    private function getDelayFromRequest(array $request): UnsignedInteger
    {
        try {
            $delay = new UnsignedInteger(
                isset($request[MessagePropertyList::DELAY]) ? (int)$request[MessagePropertyList::DELAY] : 0
            );
        } catch (ValueObjectException $e) {

            throw new InvalidArgumentException("Wrong property: '" . MessagePropertyList::DELAY . "'", 0, $e);
        }

        return $delay;
    }
}
