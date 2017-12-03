<?php

namespace EmailSender\EmailLog\Domain\Service;

use EmailSender\EmailLog\Application\Collection\EmailLogCollection;
use EmailSender\EmailLog\Domain\Contract\EmailLogRepositoryReaderInterface;
use EmailSender\EmailLog\Domain\Factory\ListRequestFactory;

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
     * @var \EmailSender\EmailLog\Domain\Factory\ListRequestFactory
     */
    private $listRequestFactory;

    /**
     * GetEmailLogService constructor.
     *
     * @param \EmailSender\EmailLog\Domain\Contract\EmailLogRepositoryReaderInterface $repositoryReader
     * @param \EmailSender\EmailLog\Domain\Factory\ListRequestFactory                 $listRequestFactory
     */
    public function __construct(
        EmailLogRepositoryReaderInterface $repositoryReader,
        ListRequestFactory $listRequestFactory
    ) {
        $this->repositoryReader          = $repositoryReader;
        $this->listRequestFactory        = $listRequestFactory;
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
        $listEmailLogsRequest = $this->listRequestFactory->create($request);

        return $this->repositoryReader->list($listEmailLogsRequest);
    }
}
