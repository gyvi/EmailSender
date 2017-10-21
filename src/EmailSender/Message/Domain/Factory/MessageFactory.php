<?php

namespace EmailSender\Message\Domain\Factory;

use EmailSender\Core\Factory\EmailAddressCollectionFactory;
use EmailSender\Core\Factory\EmailAddressFactory;
use EmailSender\Core\Collection\EmailAddressCollection;
use EmailSender\Core\Scalar\Application\Exception\ValueObjectException;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\ValueObject\EmailAddress;
use EmailSender\Message\Application\Catalog\MessagePropertyNameList;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\Core\ValueObject\Subject;
use EmailSender\Message\Domain\ValueObject\Body;
use InvalidArgumentException;

/**
 * Class MessageFactory
 *
 * @package EmailSender\Message\Domain\Factory
 */
class MessageFactory
{
    /**
     * @var \EmailSender\Core\Factory\EmailAddressFactory
     */
    private $emailAddressFactory;

    /**
     * @var \EmailSender\Core\Factory\EmailAddressCollectionFactory
     */
    private $emailAddressCollectionFactory;

    /**
     * MessageFactory constructor.
     *
     * @param \EmailSender\Core\Factory\EmailAddressFactory           $emailAddressFactory
     * @param \EmailSender\Core\Factory\EmailAddressCollectionFactory $emailAddressCollectionFactory
     */
    public function __construct(
        EmailAddressFactory $emailAddressFactory,
        EmailAddressCollectionFactory $emailAddressCollectionFactory
    ) {
        $this->emailAddressFactory           = $emailAddressFactory;
        $this->emailAddressCollectionFactory = $emailAddressCollectionFactory;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\Message\Domain\Aggregate\Message
     * @throws \InvalidArgumentException
     */
    public function create(array $request): Message
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
     * @return \EmailSender\Core\ValueObject\EmailAddress
     * @throws \InvalidArgumentException
     */
    private function getFromFromRequest(array $request): EmailAddress
    {
        try {
            $from = $this->emailAddressFactory->create($request[MessagePropertyNameList::FROM]);
        } catch (ValueObjectException $e) {
            throw new InvalidArgumentException("Wrong property: '"  . MessagePropertyNameList::FROM . "'", 0, $e);
        }

        return $from;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\Core\Collection\EmailAddressCollection
     * @throws \InvalidArgumentException
     */
    private function getToFromRequest(array $request): EmailAddressCollection
    {
        try {
            $to = $this->emailAddressCollectionFactory->createFromString($request[MessagePropertyNameList::TO]);
        } catch (ValueObjectException $e) {
            throw new InvalidArgumentException("Wrong property: '"  . MessagePropertyNameList::TO . "'", 0, $e);
        }

        if ($to->isEmpty()) {
            throw new InvalidArgumentException(
                "Wrong property: '"  . MessagePropertyNameList::TO
                . "' Property requires minimum 1 valid email address."
            );
        }

        return $to;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\Core\Collection\EmailAddressCollection
     * @throws \InvalidArgumentException
     */
    private function getCcFromRequest(array $request): EmailAddressCollection
    {
        try {
            $cc = $this->emailAddressCollectionFactory->createFromString($request[MessagePropertyNameList::CC] ?? '');
        } catch (ValueObjectException $e) {
            throw new InvalidArgumentException("Wrong property: '"  . MessagePropertyNameList::CC . "'", 0, $e);
        }

        return $cc;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\Core\Collection\EmailAddressCollection
     * @throws \InvalidArgumentException
     */
    private function getBccFromRequest(array $request): EmailAddressCollection
    {
        try {
            $bcc = $this->emailAddressCollectionFactory->createFromString($request[MessagePropertyNameList::BCC] ?? '');
        } catch (ValueObjectException $e) {
            throw new InvalidArgumentException("Wrong property: '"  . MessagePropertyNameList::BCC . "'", 0, $e);
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
            $subject = new Subject($request[MessagePropertyNameList::SUBJECT]);
        } catch (ValueObjectException $e) {
            throw new InvalidArgumentException("Wrong property: '"  . MessagePropertyNameList::SUBJECT . "'", 0, $e);
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
            $body = new Body($request[MessagePropertyNameList::BODY]);
        } catch (ValueObjectException $e) {
            throw new InvalidArgumentException("Wrong property: '"  . MessagePropertyNameList::BODY . "'", 0, $e);
        }

        return $body;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\Core\ValueObject\EmailAddress|null
     * @throws \InvalidArgumentException
     */
    private function getReplyToFromRequest(array $request): ?EmailAddress
    {
        $replyTo = null;

        try {
            if (isset($request[MessagePropertyNameList::REPLY_TO])
                && !empty(trim($request[MessagePropertyNameList::REPLY_TO]))
            ) {
                $replyTo = $this->emailAddressFactory->create($request[MessagePropertyNameList::REPLY_TO]);
            }
        } catch (ValueObjectException $e) {
            throw new InvalidArgumentException("Wrong property: '"  . MessagePropertyNameList::REPLY_TO . "'", 0, $e);
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
                isset($request[MessagePropertyNameList::DELAY]) ? (int)$request[MessagePropertyNameList::DELAY] : 0
            );
        } catch (ValueObjectException $e) {
            throw new InvalidArgumentException("Wrong property: '" . MessagePropertyNameList::DELAY . "'", 0, $e);
        }

        return $delay;
    }
}
