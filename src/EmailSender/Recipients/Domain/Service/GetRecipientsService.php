<?php

namespace EmailSender\Recipients\Domain\Service;

use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\Recipients\Domain\Aggregate\Recipients;
use EmailSender\Recipients\Domain\Builder\RecipientsBuilder;

/**
 * Class GetRecipientsService
 *
 * @package EmailSender\Recipients
 */
class GetRecipientsService
{
    /**
     * @var \EmailSender\Recipients\Domain\Builder\RecipientsBuilder
     */
    private $recipientsBuilder;

    /**
     * GetRecipientsService constructor.
     *
     * @param \EmailSender\Recipients\Domain\Builder\RecipientsBuilder $recipientsBuilder
     */
    public function __construct(RecipientsBuilder $recipientsBuilder)
    {
        $this->recipientsBuilder = $recipientsBuilder;
    }

    /**
     * @param \EmailSender\Message\Domain\Aggregate\Message $message
     *
     * @return \EmailSender\Recipients\Domain\Aggregate\Recipients
     */
    public function getRecipientsFromMessage(Message $message): Recipients
    {
        return $this->recipientsBuilder->buildRecipientsFromMessage($message);
    }

    /**
     * @param string $recipients
     *
     * @return \EmailSender\Recipients\Domain\Aggregate\Recipients
     */
    public function getRecipientsFromJson(string $recipients): Recipients
    {
        return $this->recipientsBuilder->buildRecipientsFromArray(json_decode($recipients, true));
    }
}
