<?php

namespace EmailSender\EmailLog\Domain\Service;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\EmailLog\Application\Collection\EmailLogCollection;
use EmailSender\EmailLog\Domain\Aggregate\EmailLog;
use EmailSender\EmailLog\Domain\Factory\ListRequestFactory;
use EmailSender\EmailLog\Domain\Factory\EmailLogCollectionFactory;
use EmailSender\EmailLog\Domain\Contract\EmailLogRepositoryReaderInterface;
use EmailSender\EmailLog\Domain\Factory\EmailLogFactory;

/**
 * Class GetEmailLogService
 *
 * @package EmailSender\EmailLog\Domain\Service
 */
class GetEmailLogService
{
    /**
     * @var \EmailSender\EmailLog\Domain\Contract\EmailLogRepositoryReaderInterface
     */
    private $repositoryReader;

    /**
     * @var \EmailSender\EmailLog\Domain\Factory\EmailLogFactory
     */
    private $emailLogFactory;

    /**
     * @var \EmailSender\EmailLog\Domain\Factory\EmailLogCollectionFactory
     */
    private $emailLogCollectionFactory;

    /**
     * @var \EmailSender\EmailLog\Domain\Factory\ListRequestFactory
     */
    private $listRequestFactory;

    /**
     * GetEmailLogService constructor.
     *
     * @param \EmailSender\EmailLog\Domain\Contract\EmailLogRepositoryReaderInterface $repositoryReader
     * @param \EmailSender\EmailLog\Domain\Factory\EmailLogFactory                    $emailLogFactory
     * @param \EmailSender\EmailLog\Domain\Factory\EmailLogCollectionFactory          $emailLogCollectionFactory
     * @param \EmailSender\EmailLog\Domain\Factory\ListRequestFactory                 $listRequestFactory
     */
    public function __construct(
        EmailLogRepositoryReaderInterface $repositoryReader,
        EmailLogFactory $emailLogFactory,
        EmailLogCollectionFactory $emailLogCollectionFactory,
        ListRequestFactory $listRequestFactory
    ) {
        $this->repositoryReader          = $repositoryReader;
        $this->emailLogFactory           = $emailLogFactory;
        $this->emailLogCollectionFactory = $emailLogCollectionFactory;
        $this->listRequestFactory        = $listRequestFactory;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $emailLogId
     *
     * @return \EmailSender\EmailLog\Domain\Aggregate\EmailLog
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @throws \InvalidArgumentException
     */
    public function get(UnsignedInteger $emailLogId): EmailLog
    {
        $emailLogArray = $this->repositoryReader->get($emailLogId);

        return $this->emailLogFactory->createFromArray($emailLogArray);
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\EmailLog\Application\Collection\EmailLogCollection
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @throws \InvalidArgumentException
     */
    public function list(array $request): EmailLogCollection
    {
        $listEmailLogsRequest = $this->listRequestFactory->create($request);

        $emailLogCollectionArray = $this->repositoryReader->list($listEmailLogsRequest);

        return $this->emailLogCollectionFactory->create($emailLogCollectionArray);
    }
}
