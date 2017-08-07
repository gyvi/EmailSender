<?php

namespace EmailSender\Message\Application\Service;

use EmailSender\MailAddress\Application\Service\MailAddressService;
use EmailSender\Message\Application\Contract\MessageServiceInterface;
use EmailSender\Message\Domain\Aggregate\Message;
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
        $getMessageService  = new GetMessageService($mailAddressService);

        return $getMessageService->getMessageFromRequest($request);
    }
}
