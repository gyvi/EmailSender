<?php


namespace EmailSender\Recipients\Application\Service;

use EmailSender\MailAddress\Application\Service\MailAddressService;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\Recipients\Application\Contract\RecipientsServiceInterface;
use EmailSender\Recipients\Domain\Aggregate\Recipients;
use EmailSender\Recipients\Domain\Service\GetRecipientsService;

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
        $mailAddressService   = new MailAddressService();
        $getRecipientsService = new GetRecipientsService($mailAddressService);

        return $getRecipientsService->getRecipientsFromMessage($message);
    }

    /**
     * @param string $recipients
     *
     * @return \EmailSender\Recipients\Domain\Aggregate\Recipients
     */
    public function getRecipientsFromJson(string $recipients): Recipients
    {
        $mailAddressService   = new MailAddressService();
        $getRecipientsService = new GetRecipientsService($mailAddressService);

        return $getRecipientsService->getRecipientsFromJson($recipients);
    }
}
