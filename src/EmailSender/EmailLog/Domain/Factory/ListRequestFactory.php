<?php

namespace EmailSender\EmailLog\Domain\Factory;

use EmailSender\Core\Factory\EmailAddressFactory;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\ValueObject\EmailAddress;
use EmailSender\EmailLog\Application\Catalog\ListRequestPropertyNames;
use EmailSender\EmailLog\Domain\Entity\ListRequest;
use Throwable;
use InvalidArgumentException;

/**
 * Class ListEmailLogsRequestFactory
 *
 * @package EmailSender\EmailLog
 */
class ListRequestFactory
{
    /**
     * @var \EmailSender\Core\Factory\EmailAddressFactory
     */
    private $emailAddressFactory;

    /**
     * ListEmailLogsRequestFactory constructor.
     *
     * @param \EmailSender\Core\Factory\EmailAddressFactory $emailAddressFactory
     */
    public function __construct(EmailAddressFactory $emailAddressFactory)
    {
        $this->emailAddressFactory = $emailAddressFactory;
    }

    /**
     * @param array $listRequestArray
     *
     * @return \EmailSender\EmailLog\Domain\Entity\ListRequest
     * @throws \InvalidArgumentException
     */
    public function create(array $listRequestArray): ListRequest
    {
        $listRequest = new ListRequest();

        $listRequest->setFrom($this->getFrom($listRequestArray));
        $listRequest->setPerPage($this->getPerPage($listRequestArray));
        $listRequest->setPage($this->getPage($listRequestArray));
        $listRequest->setLastComposedEmailId($this->getLastComposedEmailId($listRequestArray));

        return $listRequest;
    }

    /**
     * @param array $listRequestArray
     *
     * @return \EmailSender\Core\ValueObject\EmailAddress|null
     * @throws \InvalidArgumentException
     */
    private function getFrom(array $listRequestArray): ?EmailAddress
    {
        $from = null;

        try {
            if (!empty($listRequestArray[ListRequestPropertyNames::FROM])) {
                $from = $this->emailAddressFactory->create($listRequestArray[ListRequestPropertyNames::FROM]);
            }
        } catch (Throwable $e) {
            throw new InvalidArgumentException(
                "Wrong property: '"  . ListRequestPropertyNames::FROM . "'",
                0,
                $e
            );
        }

        return $from;
    }

    /**
     * @param array $listRequestArray
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger|null
     * @throws \InvalidArgumentException
     */
    private function getPerPage(array $listRequestArray): ?UnsignedInteger
    {
        $perPage = null;

        try {
            if (!empty($listRequestArray[ListRequestPropertyNames::PER_PAGE])
                && (int)$listRequestArray[ListRequestPropertyNames::PER_PAGE] > 0
            ) {
                $perPage = new UnsignedInteger((int)$listRequestArray[ListRequestPropertyNames::PER_PAGE]);
            }
        } catch (Throwable $e) {
            throw new InvalidArgumentException(
                "Wrong property: '"  . ListRequestPropertyNames::PER_PAGE . "'",
                0,
                $e
            );
        }

        return $perPage;
    }

    /**
     * @param array $listRequestArray
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger|null
     * @throws \InvalidArgumentException
     */
    private function getPage(array $listRequestArray): ?UnsignedInteger
    {
        $page = null;

        try {
            if (!empty($listRequestArray[ListRequestPropertyNames::PAGE])
                && (int)$listRequestArray[ListRequestPropertyNames::PAGE] > 0
            ) {
                $page = new UnsignedInteger((int)$listRequestArray[ListRequestPropertyNames::PAGE]);
            }
        } catch (Throwable $e) {
            throw new InvalidArgumentException(
                "Wrong property: '"  . ListRequestPropertyNames::PAGE . "'",
                0,
                $e
            );
        }

        return $page;
    }

    /**
     * @param array $listRequestArray
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger|null
     * @throws \InvalidArgumentException
     */
    private function getLastComposedEmailId(array $listRequestArray): ?UnsignedInteger
    {
        $lastComposedEmailId = null;

        try {
            if (!empty($listRequestArray[ListRequestPropertyNames::LAST_EMAIL_LOG_ID])
                && (int)$listRequestArray[ListRequestPropertyNames::LAST_EMAIL_LOG_ID] > 0
            ) {
                $lastComposedEmailId = new UnsignedInteger(
                    (int)$listRequestArray[
                        ListRequestPropertyNames::LAST_EMAIL_LOG_ID
                    ]
                );
            }
        } catch (Throwable $e) {
            throw new InvalidArgumentException(
                "Wrong property: '"  . ListRequestPropertyNames::LAST_EMAIL_LOG_ID . "'",
                0,
                $e
            );
        }

        return $lastComposedEmailId;
    }
}
