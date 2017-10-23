<?php

namespace EmailSender\Email\Domain\Factory;

use EmailSender\Core\Factory\EmailAddressCollectionFactory;
use EmailSender\Core\Factory\EmailAddressFactory;
use EmailSender\Core\Collection\EmailAddressCollection;
use EmailSender\Core\Scalar\Application\Exception\ValueObjectException;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\ValueObject\EmailAddress;
use EmailSender\Email\Application\Catalog\EmailPropertyNameList;
use EmailSender\Email\Domain\Aggregate\Email;
use EmailSender\Core\ValueObject\Subject;
use EmailSender\Email\Domain\ValueObject\Body;
use InvalidArgumentException;

/**
 * Class EmailFactory
 *
 * @package EmailSender\Email
 */
class EmailFactory
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
     * EmailFactory constructor.
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
     * @return \EmailSender\Email\Domain\Aggregate\Email
     *
     * @throws \InvalidArgumentException
     */
    public function create(array $request): Email
    {
        $from    = $this->getFrom($request);
        $to      = $this->getTo($request);
        $cc      = $this->getCc($request);
        $bcc     = $this->getBcc($request);
        $subject = $this->getSubject($request);
        $body    = $this->getBody($request);
        $replyTo = $this->getReplyTo($request);
        $delay   = $this->getDelay($request);

        return new Email($from, $to, $cc, $bcc, $subject, $body, $replyTo, $delay);
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\Core\ValueObject\EmailAddress
     *
     * @throws \InvalidArgumentException
     */
    private function getFrom(array $request): EmailAddress
    {
        try {
            $from = $this->emailAddressFactory->create($request[EmailPropertyNameList::FROM]);
        } catch (ValueObjectException $e) {
            throw new InvalidArgumentException("Wrong property: '"  . EmailPropertyNameList::FROM . "'", 0, $e);
        }

        return $from;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\Core\Collection\EmailAddressCollection
     *
     * @throws \InvalidArgumentException
     */
    private function getTo(array $request): EmailAddressCollection
    {
        try {
            $to = $this->emailAddressCollectionFactory->createFromString($request[EmailPropertyNameList::TO]);
        } catch (ValueObjectException $e) {
            throw new InvalidArgumentException("Wrong property: '"  . EmailPropertyNameList::TO . "'", 0, $e);
        }

        if ($to->isEmpty()) {
            throw new InvalidArgumentException(
                "Wrong property: '"  . EmailPropertyNameList::TO
                . "' Property requires minimum 1 valid email address."
            );
        }

        return $to;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\Core\Collection\EmailAddressCollection
     *
     * @throws \InvalidArgumentException
     */
    private function getCc(array $request): EmailAddressCollection
    {
        try {
            $cc = $this->emailAddressCollectionFactory->createFromString($request[EmailPropertyNameList::CC] ?? '');
        } catch (ValueObjectException $e) {
            throw new InvalidArgumentException("Wrong property: '"  . EmailPropertyNameList::CC . "'", 0, $e);
        }

        return $cc;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\Core\Collection\EmailAddressCollection
     *
     * @throws \InvalidArgumentException
     */
    private function getBcc(array $request): EmailAddressCollection
    {
        try {
            $bcc = $this->emailAddressCollectionFactory->createFromString($request[EmailPropertyNameList::BCC] ?? '');
        } catch (ValueObjectException $e) {
            throw new InvalidArgumentException("Wrong property: '"  . EmailPropertyNameList::BCC . "'", 0, $e);
        }

        return $bcc;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\Core\ValueObject\Subject
     *
     * @throws \InvalidArgumentException
     */
    private function getSubject(array $request): Subject
    {
        try {
            $subject = new Subject($request[EmailPropertyNameList::SUBJECT]);
        } catch (ValueObjectException $e) {
            throw new InvalidArgumentException("Wrong property: '"  . EmailPropertyNameList::SUBJECT . "'", 0, $e);
        }

        return $subject;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\Email\Domain\ValueObject\Body
     *
     * @throws \InvalidArgumentException
     */
    private function getBody(array $request): Body
    {
        try {
            $body = new Body($request[EmailPropertyNameList::BODY]);
        } catch (ValueObjectException $e) {
            throw new InvalidArgumentException("Wrong property: '"  . EmailPropertyNameList::BODY . "'", 0, $e);
        }

        return $body;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\Core\ValueObject\EmailAddress|null
     *
     * @throws \InvalidArgumentException
     */
    private function getReplyTo(array $request): ?EmailAddress
    {
        $replyTo = null;

        try {
            if (isset($request[EmailPropertyNameList::REPLY_TO])
                && !empty(trim($request[EmailPropertyNameList::REPLY_TO]))
            ) {
                $replyTo = $this->emailAddressFactory->create($request[EmailPropertyNameList::REPLY_TO]);
            }
        } catch (ValueObjectException $e) {
            throw new InvalidArgumentException("Wrong property: '"  . EmailPropertyNameList::REPLY_TO . "'", 0, $e);
        }

        return $replyTo;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     *
     * @throws \InvalidArgumentException
     */
    private function getDelay(array $request): UnsignedInteger
    {
        try {
            $delay = new UnsignedInteger(
                isset($request[EmailPropertyNameList::DELAY]) ? (int)$request[EmailPropertyNameList::DELAY] : 0
            );
        } catch (ValueObjectException $e) {
            throw new InvalidArgumentException("Wrong property: '" . EmailPropertyNameList::DELAY . "'", 0, $e);
        }

        return $delay;
    }
}
