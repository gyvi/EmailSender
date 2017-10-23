<?php

namespace EmailSender\EmailLog\Domain\Entity;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\ValueObject\EmailAddress;

/**
 * Class ListRequest
 *
 * @package EmailSender\EmailLog\Domain\Entity
 */
class ListRequest
{
    /** Default value for perPage property. */
    const DEFAULT_PER_PAGE = 50;

    /**
     * @var \EmailSender\Core\ValueObject\EmailAddress|null
     */
    private $from;

    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger|null
     */
    private $perPage;

    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger|null
     */
    private $page;

    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger|null
     */
    private $lastComposedEmailId;

    /**
     * @return \EmailSender\Core\ValueObject\EmailAddress|null
     */
    public function getFrom(): ?EmailAddress
    {
        return $this->from;
    }

    /**
     * @param \EmailSender\Core\ValueObject\EmailAddress|null $from
     */
    public function setFrom(?EmailAddress $from): void
    {
        $this->from = $from;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger|null
     */
    public function getPerPage(): ?UnsignedInteger
    {
        return $this->perPage;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger|null $perPage
     */
    public function setPerPage(?UnsignedInteger $perPage): void
    {
        $this->perPage = $perPage;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger|null
     */
    public function getPage(): ?UnsignedInteger
    {
        return $this->page;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger|null $page
     */
    public function setPage(?UnsignedInteger $page): void
    {
        $this->page = $page;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger|null
     */
    public function getLastComposedEmailId(): ?UnsignedInteger
    {
        return $this->lastComposedEmailId;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger|null $lastComposedEmailId
     */
    public function setLastComposedEmailId(?UnsignedInteger $lastComposedEmailId): void
    {
        $this->lastComposedEmailId = $lastComposedEmailId;
    }
}
