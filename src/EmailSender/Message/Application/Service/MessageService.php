<?php

namespace EmailSender\Message\Application\Service;

use EmailSender\Message\Application\Contract\MessageServiceInterface;
use EmailSender\Message\Domain\Aggregate\Message;

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

        return new Message();
    }
}
