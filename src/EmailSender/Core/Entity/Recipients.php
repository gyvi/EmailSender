<?php

namespace EmailSender\Core\Entity;

use EmailSender\Core\Collection\EmailAddressCollection;
use JsonSerializable;

/**
 * Class Recipients
 *
 * @package EmailSender\Core
 */
class Recipients implements JsonSerializable
{
    /**
     * @var \EmailSender\Core\Collection\EmailAddressCollection
     */
    private $to;

    /**
     * @var \EmailSender\Core\Collection\EmailAddressCollection
     */
    private $cc;

    /**
     * @var \EmailSender\Core\Collection\EmailAddressCollection
     */
    private $bcc;

    /**
     * Recipients constructor.
     *
     * @param \EmailSender\Core\Collection\EmailAddressCollection $to
     * @param \EmailSender\Core\Collection\EmailAddressCollection $cc
     * @param \EmailSender\Core\Collection\EmailAddressCollection $bcc
     */
    public function __construct(EmailAddressCollection $to, EmailAddressCollection $cc, EmailAddressCollection $bcc)
    {
        $this->to  = $to;
        $this->cc  = $cc;
        $this->bcc = $bcc;
    }

    /**
     * @return \EmailSender\Core\Collection\EmailAddressCollection
     */
    public function getTo(): EmailAddressCollection
    {
        return $this->to;
    }

    /**
     * @return \EmailSender\Core\Collection\EmailAddressCollection
     */
    public function getCc(): EmailAddressCollection
    {
        return $this->cc;
    }

    /**
     * @return \EmailSender\Core\Collection\EmailAddressCollection
     */
    public function getBcc(): EmailAddressCollection
    {
        return $this->bcc;
    }

    /**
     * Return with the Json serializable array.
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
