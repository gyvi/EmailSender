<?php

namespace EmailSender\EmailLog\Domain\Factory;

use EmailSender\EmailLog\Application\Collection\EmailLogCollection;

/**
 * Class EmailLogCollectionFactory
 *
 * @package EmailSender\EmailLog
 */
class EmailLogCollectionFactory
{
    /**
     * @var \EmailSender\EmailLog\Domain\Factory\EmailLogFactory
     */
    private $emailLogFactory;

    /**
     * EmailLogCollectionBuilder constructor.
     *
     * @param \EmailSender\EmailLog\Domain\Factory\EmailLogFactory $emailLogFactory
     */
    public function __construct(EmailLogFactory $emailLogFactory)
    {
        $this->emailLogFactory = $emailLogFactory;
    }

    /**
     * @param array $emailLogCollectionArray
     *
     * @return \EmailSender\EmailLog\Application\Collection\EmailLogCollection
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @throws \InvalidArgumentException
     */
    public function create(array $emailLogCollectionArray): EmailLogCollection
    {
        $emailLogCollection = new EmailLogCollection();

        /** @var array $emailLogArray */
        foreach ($emailLogCollectionArray as $emailLogArray) {
            $emailLogCollection->add($this->emailLogFactory->createFromArray($emailLogArray));
        }

        return $emailLogCollection;
    }
}
