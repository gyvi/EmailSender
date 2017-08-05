<?php

namespace EmailSender\Recipients\Domain;

use EmailSender\MailAddress\Application\Collection\MailAddressCollection;

/**
 * Class Recipients
 *
 * @package EmailSender\Recipients
 */
class Recipients
{
    /**
     * @var \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     */
    private $to;

    /**
     * @var \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     */
    private $cc;

    /**
     * @var \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     */
    private $bcc;

    /**
     * Recipients constructor.
     *
     * @param \EmailSender\MailAddress\Application\Collection\MailAddressCollection $to
     * @param \EmailSender\MailAddress\Application\Collection\MailAddressCollection $cc
     * @param \EmailSender\MailAddress\Application\Collection\MailAddressCollection $bcc
     */
    public function __construct(MailAddressCollection $to, MailAddressCollection $cc, MailAddressCollection $bcc)
    {
        $this->to  = $to;
        $this->cc  = $cc;
        $this->bcc = $bcc;
    }

    /**
     * @return \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     */
    public function getTo(): MailAddressCollection
    {
        return $this->to;
    }

    /**
     * @return \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     */
    public function getCc(): MailAddressCollection
    {
        return $this->cc;
    }

    /**
     * @return \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     */
    public function getBcc(): MailAddressCollection
    {
        return $this->bcc;
    }
}
