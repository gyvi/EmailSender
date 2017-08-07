<?php

namespace EmailSender\Recipients\Domain\Service;

use EmailSender\MailAddress\Application\Service\MailAddressService;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\Recipients\Domain\Aggregate\Recipients;
use EmailSender\Recipients\Domain\Builder\RecipientsBuilder;

/**
 * Class GetRecipientsService
 *
 * @package EmailSender\Recipients
 */
class GetRecipientsService
{
    /**
     * @var \EmailSender\MailAddress\Application\Service\MailAddressService
     */
    private $mailAddressService;

    /**
     * GetRecipientsService constructor.
     *
     * @param \EmailSender\MailAddress\Application\Service\MailAddressService $mailAddressService
     */
    public function __construct(MailAddressService $mailAddressService)
    {
        $this->mailAddressService = $mailAddressService;
    }

    public function getRecipientsFromMessage(Message $message): Recipients
    {
        $recipientsBuilder = new RecipientsBuilder($this->mailAddressService);

        return $recipientsBuilder->buildRecipientsFromMessage($message);
    }

    /**
     * @param string $recipients
     *
     * @return \EmailSender\Recipients\Domain\Aggregate\Recipients
     */
    public function getRecipientsFromJson(string $recipients): Recipients
    {
        $recipientsBuilder = new RecipientsBuilder($this->mailAddressService);

        return $recipientsBuilder->buildRecipientsFromArray(json_decode($recipients, true));
    }
}
