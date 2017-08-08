<?php

namespace EmailSender\Message\Domain\Service;

use EmailSender\MailAddress\Application\Contract\MailAddressServiceInterface;
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
     * @var \EmailSender\MailAddress\Application\Contract\MailAddressServiceInterface
     */
    private $mailAddressService;

    /**
     * @var \EmailSender\Message\Domain\Builder\MessageBuilder
     */
    private $messageBuilder;

    /**
     * GetMessageService constructor.
     *
     * @param \EmailSender\MailAddress\Application\Contract\MailAddressServiceInterface $mailAddressService
     * @param \EmailSender\Message\Domain\Builder\MessageBuilder                        $messageBuilder
     */
    public function __construct(MailAddressServiceInterface $mailAddressService, MessageBuilder $messageBuilder)
    {
        $this->mailAddressService = $mailAddressService;
        $this->messageBuilder     = $messageBuilder;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\Message\Domain\Aggregate\Message
     */
    public function getMessageFromRequest(array $request): Message
    {
        return $this->messageBuilder->buildMessageFromRequest($request);
    }
}
