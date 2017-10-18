<?php

namespace EmailSender\MessageLog\Application\Collection;

use EmailSender\Core\Collection\Collection;
use EmailSender\MessageLog\Domain\Aggregate\MessageLog;

/**
 * Class MessageLogCollection
 *
 * @package EmailSender\MessageLog
 */
class MessageLogCollection extends Collection
{
    /**
     * @param \EmailSender\MessageLog\Domain\Aggregate\MessageLog $item
     *
     * @throws \InvalidArgumentException
     */
    public function add($item): void
    {
        parent::add($item);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return MessageLog::class;
    }
}
