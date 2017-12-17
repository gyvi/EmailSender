<?php

namespace EmailSender\EmailLog\Domain\Factory;

use EmailSender\Core\Factory\EmailAddressFactory;
use EmailSender\Core\Scalar\Application\Exception\ValueObjectException;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\ValueObject\EmailAddress;
use EmailSender\EmailLog\Application\Catalog\ListEmailLogRequestPropertyNameList;
use EmailSender\EmailLog\Domain\Entity\ListEmailLogRequest;
use Throwable;
use InvalidArgumentException;

/**
 * Class ListEmailLogsRequestFactory
 *
 * @package EmailSender\EmailLog
 */
class ListEmailLogRequestFactory
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
     * @param array $listEmailLogRequestArray
     *
     * @return \EmailSender\EmailLog\Domain\Entity\ListEmailLogRequest
     *
     * @throws \InvalidArgumentException
     */
    public function create(array $listEmailLogRequestArray): ListEmailLogRequest
    {
        $listEmailLogRequest = new ListEmailLogRequest();

        $listEmailLogRequest->setFrom($this->getFrom($listEmailLogRequestArray));

        $listEmailLogRequest->setPerPage(
            $this->getUnsignedInteger(ListEmailLogRequestPropertyNameList::PER_PAGE, $listEmailLogRequestArray)
        );

        $listEmailLogRequest->setPage(
            $this->getUnsignedInteger(ListEmailLogRequestPropertyNameList::PAGE, $listEmailLogRequestArray)
        );

        $listEmailLogRequest->setLastComposedEmailId(
            $this->getUnsignedInteger(
                ListEmailLogRequestPropertyNameList::LAST_COMPOSED_EMAIL_ID,
                $listEmailLogRequestArray
            )
        );

        return $listEmailLogRequest;
    }

    /**
     * @param array $listEmailLogRequestArray
     *
     * @return \EmailSender\Core\ValueObject\EmailAddress|null
     *
     * @throws \InvalidArgumentException
     */
    private function getFrom(array $listEmailLogRequestArray): ?EmailAddress
    {
        $from = null;

        try {
            if (!empty($listEmailLogRequestArray[ListEmailLogRequestPropertyNameList::FROM])) {
                $from = $this->emailAddressFactory->create(
                    $listEmailLogRequestArray[ListEmailLogRequestPropertyNameList::FROM]
                );
            }
        } catch (Throwable $e) {
            throw new InvalidArgumentException(
                "Wrong property: '"  . ListEmailLogRequestPropertyNameList::FROM . "'",
                0,
                $e
            );
        }

        return $from;
    }

    /**
     * @param string $propertyName
     * @param array  $listEmailLogRequestArray
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger|null
     *
     * @throws \InvalidArgumentException
     */
    private function getUnsignedInteger(string $propertyName, array $listEmailLogRequestArray): ?UnsignedInteger
    {
        $property = null;

        try {
            if (!empty($listEmailLogRequestArray[$propertyName])) {
                if (!is_numeric($listEmailLogRequestArray[$propertyName])) {
                    throw new ValueObjectException('Invalid unsigned integer value');
                }

                if ((int)$listEmailLogRequestArray[$propertyName] !== 0) {
                    $property = new UnsignedInteger((int)$listEmailLogRequestArray[$propertyName]);
                }
            }
        } catch (Throwable $e) {
            throw new InvalidArgumentException(
                "Wrong property: '"  . $propertyName . "'",
                0,
                $e
            );
        }

        return $property;
    }
}
