<?php

namespace EmailSender\MessageLog\Domain\Builder;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MailAddress\Application\Contract\MailAddressServiceInterface;
use EmailSender\MailAddress\Domain\Aggregate\MailAddress;
use EmailSender\MessageLog\Application\Catalog\ListMessageLogsRequestPropertyNames;
use EmailSender\MessageLog\Domain\Entity\ListMessageLogsRequest;
use Throwable;
use InvalidArgumentException;

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

        $listMessageLogsRequest->setFrom($this->getFromFromRequest($listMessageLogsRequestArray));
        $listMessageLogsRequest->setRows($this->getRowsFromRequest($listMessageLogsRequestArray));
        $listMessageLogsRequest->setPage($this->getPageFromRequest($listMessageLogsRequestArray));
        $listMessageLogsRequest->setLastMessageId($this->getLastMessageIdFromRequest($listMessageLogsRequestArray));

        return $listMessageLogsRequest;
    }

    /**
     * @param array $listMessageLogsRequestArray
     *
     * @return \EmailSender\MailAddress\Domain\Aggregate\MailAddress|null
     */
    private function getFromFromRequest(array $listMessageLogsRequestArray): ?MailAddress
    {
        $from = null;

        try {
            if (!empty($listMessageLogsRequestArray[ListMessageLogsRequestPropertyNames::FROM])) {
                $from = $this->mailAddressService->getMailAddress(
                            $listMessageLogsRequestArray[ListMessageLogsRequestPropertyNames::FROM]
                        );
            }
        } catch (Throwable $e) {

            throw new InvalidArgumentException(
                "Wrong property: '"  . ListMessageLogsRequestPropertyNames::FROM . "'", 0, $e
            );
        }

        return $from;
    }

    /**
     * @param array $listMessageLogsRequestArray
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger|null
     */
    private function getRowsFromRequest(array $listMessageLogsRequestArray): ?UnsignedInteger
    {
        $rows = null;

        try {
            if (!empty($listMessageLogsRequestArray[ListMessageLogsRequestPropertyNames::ROWS])
                && intval($listMessageLogsRequestArray[ListMessageLogsRequestPropertyNames::ROWS]) > 0
            ) {
                $rows = new UnsignedInteger(
                            (int)$listMessageLogsRequestArray[ListMessageLogsRequestPropertyNames::ROWS]
                        );
            }
        } catch (Throwable $e) {

            throw new InvalidArgumentException(
                "Wrong property: '"  . ListMessageLogsRequestPropertyNames::ROWS . "'", 0, $e
            );
        }

        return $rows;
    }

    /**
     * @param array $listMessageLogsRequestArray
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger|null
     */
    private function getPageFromRequest(array $listMessageLogsRequestArray): ?UnsignedInteger
    {
        $page = null;

        try {
            if (!empty($listMessageLogsRequestArray[ListMessageLogsRequestPropertyNames::PAGE])
                && intval($listMessageLogsRequestArray[ListMessageLogsRequestPropertyNames::PAGE]) > 0
            ) {
                $page = new UnsignedInteger(
                            (int)$listMessageLogsRequestArray[ListMessageLogsRequestPropertyNames::PAGE]
                        );
            }

        } catch (Throwable $e) {

            throw new InvalidArgumentException(
                "Wrong property: '"  . ListMessageLogsRequestPropertyNames::PAGE . "'", 0, $e
            );
        }

        return $page;
    }

    /**
     * @param array $listMessageLogsRequestArray
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger|null
     */
    private function getLastMessageIdFromRequest(array $listMessageLogsRequestArray): ?UnsignedInteger
    {
        $lastMessageId = null;

        try {
            if (!empty($listMessageLogsRequestArray[ListMessageLogsRequestPropertyNames::LAST_MESSAGE_ID])
                && intval($listMessageLogsRequestArray[ListMessageLogsRequestPropertyNames::LAST_MESSAGE_ID]) > 0
            ) {
                $lastMessageId = new UnsignedInteger(
                                    (int)$listMessageLogsRequestArray[
                                        ListMessageLogsRequestPropertyNames::LAST_MESSAGE_ID
                                    ]
                                 );
            }
        } catch (Throwable $e) {

            throw new InvalidArgumentException(
                "Wrong property: '"  . ListMessageLogsRequestPropertyNames::LAST_MESSAGE_ID . "'", 0, $e
            );
        }

        return $lastMessageId;
    }
}
