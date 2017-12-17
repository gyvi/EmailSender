<?php

namespace EmailSender\EmailLog\Domain\Service;

use EmailSender\EmailLog\Application\Collection\EmailLogCollection;
use EmailSender\EmailLog\Domain\Contract\EmailLogRepositoryReaderInterface;
use EmailSender\EmailLog\Domain\Factory\ListEmailLogRequestFactory;

/**
 * Class ListEmailLogService
 *
 * @package EmailSender\EmailLog
 */
class ListEmailLogService
{
    /**
     * @var \EmailSender\EmailLog\Domain\Contract\EmailLogRepositoryReaderInterface
     */
    private $repositoryReader;

    /**
     * @var \EmailSender\EmailLog\Domain\Factory\ListEmailLogRequestFactory
     */
    private $listEmailLogRequestFactory;

    /**
     * GetEmailLogService constructor.
     *
     * @param \EmailSender\EmailLog\Domain\Contract\EmailLogRepositoryReaderInterface $repositoryReader
     * @param \EmailSender\EmailLog\Domain\Factory\ListEmailLogRequestFactory         $listEmailLogRequestFactory
     */
    public function __construct(
        EmailLogRepositoryReaderInterface $repositoryReader,
        ListEmailLogRequestFactory $listEmailLogRequestFactory
    ) {
        $this->repositoryReader   = $repositoryReader;
        $this->listEmailLogRequestFactory = $listEmailLogRequestFactory;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\EmailLog\Application\Collection\EmailLogCollection
     *
     * @throws \InvalidArgumentException
     */
    public function list(array $request): EmailLogCollection
    {
        $listEmailLogsRequest = $this->listEmailLogRequestFactory->create($request);

        return $this->repositoryReader->list($listEmailLogsRequest);
    }
}
