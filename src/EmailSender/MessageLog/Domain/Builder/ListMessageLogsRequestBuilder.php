<?php

namespace EmailSender\MessageLog\Domain\Builder;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MailAddress\Application\Contract\MailAddressServiceInterface;
use EmailSender\MessageLog\Application\Catalog\ListMessageLogsRequestPropertyNames;
use EmailSender\MessageLog\Domain\Entity\ListMessageLogsRequest;

/**
 * Class ListMessageLogsRequestBuilder
 *
 * @package EmailSender\MessageLog
 */
class ListMessageLogsRequestBuilder
{
    /**
     * @var \EmailSender\MailAddress\Application\Contract\MailAddressServiceInterface
     */
    private $mailAddressService;

    /**
     * ListMessageLogsRequestBuilder constructor.
     *
     * @param \EmailSender\MailAddress\Application\Contract\MailAddressServiceInterface $mailAddressService
     */
    public function __construct(MailAddressServiceInterface $mailAddressService)
    {
        $this->mailAddressService = $mailAddressService;
    }

    /**
     * @param array $listMessageLogsRequestArray
     *
     * @return \EmailSender\MessageLog\Domain\Entity\ListMessageLogsRequest
     */
    public function buildListMessageLogsRequestFromArray(array $listMessageLogsRequestArray): ListMessageLogsRequest
    {
        $listMessageLogsRequest = new ListMessageLogsRequest();

        if (!empty($listMessageLogsRequestArray[ListMessageLogsRequestPropertyNames::FROM])) {
            $listMessageLogsRequest->setFrom(
                $this->mailAddressService->getMailAddress(
                    $listMessageLogsRequestArray[ListMessageLogsRequestPropertyNames::FROM]
                )
            );
        }

        if (!empty($listMessageLogsRequestArray[ListMessageLogsRequestPropertyNames::ROWS])
            && intval($listMessageLogsRequestArray[ListMessageLogsRequestPropertyNames::ROWS]) > 0
        ) {
            $listMessageLogsRequest->setRows(
                new UnsignedInteger((int)$listMessageLogsRequestArray[ListMessageLogsRequestPropertyNames::ROWS])
            );
        }

        if (!empty($listMessageLogsRequestArray[ListMessageLogsRequestPropertyNames::PAGE])
            && intval($listMessageLogsRequestArray[ListMessageLogsRequestPropertyNames::PAGE]) > 0
        ) {
            $listMessageLogsRequest->setPage(
                new UnsignedInteger((int)$listMessageLogsRequestArray[ListMessageLogsRequestPropertyNames::PAGE])
            );
        }

        if (!empty($listMessageLogsRequestArray[ListMessageLogsRequestPropertyNames::LAST_MESSAGE_ID])
            && intval($listMessageLogsRequestArray[ListMessageLogsRequestPropertyNames::LAST_MESSAGE_ID]) > 0
        ) {
            $listMessageLogsRequest->setLastMessageId(
                new UnsignedInteger(
                    (int)$listMessageLogsRequestArray[ListMessageLogsRequestPropertyNames::LAST_MESSAGE_ID]
                )
            );
        }

        return $listMessageLogsRequest;
    }
}
