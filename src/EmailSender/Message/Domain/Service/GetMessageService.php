<?php

namespace EmailSender\Message\Domain\Service;

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
     * @var \EmailSender\Message\Domain\Builder\MessageBuilder
     */
    private $messageBuilder;

    /**
     * GetMessageService constructor.
     *
     * @param \EmailSender\Message\Domain\Builder\MessageBuilder $messageBuilder
     */
    public function __construct(MessageBuilder $messageBuilder)
    {
        $this->messageBuilder = $messageBuilder;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\Message\Domain\Aggregate\Message
     * @throws \InvalidArgumentException
     */
    public function getMessageFromRequest(array $request): Message
    {
        return $this->messageBuilder->buildMessageFromRequest($request);
    }
}
