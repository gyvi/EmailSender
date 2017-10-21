<?php

namespace EmailSender\Message\Application\Service;

use EmailSender\Core\Factory\EmailAddressCollectionFactory;
use EmailSender\Core\Factory\EmailAddressFactory;
use EmailSender\Message\Application\Contract\MessageServiceInterface;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\Message\Domain\Factory\MessageFactory;
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
     * @throws \InvalidArgumentException
     */
    public function getMessageFromRequest(array $request): Message
    {
        $emailAddressFactory           = new EmailAddressFactory();
        $emailAddressCollectionFactory = new EmailAddressCollectionFactory($emailAddressFactory);
        $messageFactory                = new MessageFactory($emailAddressFactory, $emailAddressCollectionFactory);
        $getMessageService             = new GetMessageService($messageFactory);

        return $getMessageService->getMessageFromRequest($request);
    }
}
