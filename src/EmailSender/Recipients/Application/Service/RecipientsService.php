<?php


namespace EmailSender\Recipients\Application\Service;

use EmailSender\MailAddress\Application\Service\MailAddressService;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\Recipients\Application\Contract\RecipientsServiceInterface;
use EmailSender\Recipients\Domain\Aggregate\Recipients;
use EmailSender\Recipients\Domain\Builder\RecipientsBuilder;

/**
 * Class RecipientsService
 *
 * @package EmailSender\Recipients
 */
class RecipientsService implements RecipientsServiceInterface
{
    /**
     * @param \EmailSender\Message\Domain\Aggregate\Message $message
     *
     * @return \EmailSender\Recipients\Domain\Aggregate\Recipients
     */
    public function getRecipientsFromMessage(Message $message): Recipients
    {
        $mailAddressService = new MailAddressService();
        $recipientsBuilder  = new RecipientsBuilder($mailAddressService);

        return $recipientsBuilder->buildRecipientsFromMessage($message);
    }

    /**
     * @param array $recipientsArray
     *
     * @return \EmailSender\Recipients\Domain\Aggregate\Recipients
     */
    public function getRecipientsFromArray(array $recipientsArray): Recipients
    {
        $mailAddressService = new MailAddressService();
        $recipientsBuilder  = new RecipientsBuilder($mailAddressService);

        return $recipientsBuilder->buildRecipientsFromArray($recipientsArray);
    }
}
