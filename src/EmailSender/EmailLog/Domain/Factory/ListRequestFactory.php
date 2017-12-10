<?php

namespace EmailSender\EmailLog\Domain\Factory;

use EmailSender\Core\Factory\EmailAddressFactory;
use EmailSender\Core\Scalar\Application\Exception\ValueObjectException;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\ValueObject\EmailAddress;
use EmailSender\EmailLog\Application\Catalog\ListRequestPropertyNameList;
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
     *
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
     *
     * @throws \InvalidArgumentException
     */
    private function getFrom(array $listRequestArray): ?EmailAddress
    {
        $from = null;

        try {
            if (!empty($listRequestArray[ListRequestPropertyNameList::FROM])) {
                $from = $this->emailAddressFactory->create($listRequestArray[ListRequestPropertyNameList::FROM]);
            }
        } catch (Throwable $e) {
            throw new InvalidArgumentException(
                "Wrong property: '"  . ListRequestPropertyNameList::FROM . "'",
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
     *
     * @throws \InvalidArgumentException
     */
    private function getPerPage(array $listRequestArray): ?UnsignedInteger
    {
        $perPage = null;

        try {
            if (!empty($listRequestArray[ListRequestPropertyNameList::PER_PAGE])) {
                $perPage = $this->getUnsignedInteger($listRequestArray[ListRequestPropertyNameList::PER_PAGE]);
            }
        } catch (Throwable $e) {
            throw new InvalidArgumentException(
                "Wrong property: '"  . ListRequestPropertyNameList::PER_PAGE . "'",
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
     *
     * @throws \InvalidArgumentException
     */
    private function getPage(array $listRequestArray): ?UnsignedInteger
    {
        $page = null;

        try {
            if (!empty($listRequestArray[ListRequestPropertyNameList::PAGE])) {
                $page = $this->getUnsignedInteger($listRequestArray[ListRequestPropertyNameList::PAGE]);
            }
        } catch (Throwable $e) {
            throw new InvalidArgumentException(
                "Wrong property: '"  . ListRequestPropertyNameList::PAGE . "'",
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
     *
     * @throws \InvalidArgumentException
     */
    private function getLastComposedEmailId(array $listRequestArray): ?UnsignedInteger
    {
        $lastComposedEmailId = null;

        try {
            if (!empty($listRequestArray[ListRequestPropertyNameList::LAST_EMAIL_LOG_ID])) {
                $lastComposedEmailId = $this->getUnsignedInteger(
                    $listRequestArray[ListRequestPropertyNameList::LAST_EMAIL_LOG_ID]
                );
            }
        } catch (Throwable $e) {
            throw new InvalidArgumentException(
                "Wrong property: '"  . ListRequestPropertyNameList::LAST_EMAIL_LOG_ID . "'",
                0,
                $e
            );
        }

        return $lastComposedEmailId;
    }

    /**
     * @param mixed $fieldValue
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger|null
     *
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     */
    private function getUnsignedInteger($fieldValue): ?UnsignedInteger
    {
        if (!is_numeric($fieldValue)) {
            throw new ValueObjectException('Invalid unsigned integer value');
        }

        if ((int)$fieldValue !== 0) {
            return new UnsignedInteger((int)$fieldValue);
        }

        return null;
    }
}
