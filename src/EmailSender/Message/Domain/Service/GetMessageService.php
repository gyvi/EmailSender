<?php

namespace EmailSender\Message\Domain\Service;

use EmailSender\MailAddress\Application\Service\MailAddressService;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\Message\Domain\Builder\MessageBuilder;

/**
 * Class GetMessageService
 *
 * @package EmailSender\Message
 */
class GetMessageService
{
    /**
     * @var \EmailSender\MailAddress\Application\Service\MailAddressService
     */
    private $mailAddressService;

    /**
     * GetMessageService constructor.
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
    public function getMessageFromRequest(array $request): Message
    {
        $messageBuilder = new MessageBuilder($this->mailAddressService);

        return $messageBuilder->buildMessageFromRequest($request);
    }
}
