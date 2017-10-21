<?php

namespace EmailSender\Message\Domain\Service;

use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\Message\Domain\Factory\MessageFactory;

/**
 * Class GetMessageService
 *
 * @package EmailSender\Message
 */
class GetMessageService
{
    /**
     * @var \EmailSender\Message\Domain\Factory\MessageFactory
     */
    private $messageFactory;

    /**
     * GetMessageService constructor.
     *
     * @param \EmailSender\Message\Domain\Factory\MessageFactory $messageFactory
     */
    public function __construct(MessageFactory $messageFactory)
    {
        $this->messageFactory = $messageFactory;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\Message\Domain\Aggregate\Message
     * @throws \InvalidArgumentException
     */
    public function getMessageFromRequest(array $request): Message
    {
        return $this->messageFactory->create($request);
    }
}
