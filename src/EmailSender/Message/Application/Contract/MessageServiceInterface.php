<?php

namespace EmailSender\Message\Application\Contract;

use EmailSender\Message\Domain\Aggregate\Message;

/**
 * Interface MessageServiceInterface
 *
 * @package EmailSender\Message\Application\Contract
 */
interface MessageServiceInterface
{
    /**
     * @param array $request
     *
     * @return \EmailSender\Message\Domain\Aggregate\Message
     */
    public function getMessageFromRequest(array $request): Message;
}
