<?php

namespace EmailSender\Message\Application\Service;

use EmailSender\MailAddress\Application\Service\MailAddressService;
use EmailSender\Message\Application\Contract\MessageServiceInterface;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\Message\Domain\Builder\MessageBuilder;
use EmailSender\Message\Domain\Service\GetMessageService;

/**
 * Class MessageService
 *
 * @package EmailSender\Message
 */
class MessageService implements MessageServiceInterface
{
    /**
     * @param array $request
     *
     * @return \EmailSender\Message\Domain\Aggregate\Message
     */
    public function getMessageFromRequest(array $request): Message
    {
        $mailAddressService = new MailAddressService();
        $messageBuilder     = new MessageBuilder($mailAddressService);
        $getMessageService  = new GetMessageService($mailAddressService, $messageBuilder);

        return $getMessageService->getMessageFromRequest($request);
    }
}
