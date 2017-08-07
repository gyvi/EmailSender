<?php

namespace EmailSender\MessageStore\Domain\Service;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MessageStore\Domain\Builder\MessageStoreBuilder;
use EmailSender\MessageStore\Domain\Contract\EmailComposerInterface;
use EmailSender\MessageStore\Domain\Contract\MessageStoreRepositoryReaderInterface;
use EmailSender\MessageStore\Domain\Aggregate\MessageStore;
use EmailSender\Recipients\Application\Service\RecipientsService;

/**
 * Class GetMessageStoreService
 *
 * @package EmailSender\MessageStore
 */
class GetMessageStoreService
{
    /**
     * @var \EmailSender\MessageStore\Domain\Contract\MessageStoreRepositoryReaderInterface
     */
    private $repositoryReader;

    /**
     * @var \EmailSender\MessageStore\Domain\Contract\EmailComposerInterface
     */
    private $emailComposer;

    /**
     * @var \EmailSender\Recipients\Application\Service\RecipientsService
     */
    private $recipientsService;

    /**
     * GetMessageStoreService constructor.
     *
     * @param \EmailSender\MessageStore\Domain\Contract\MessageStoreRepositoryReaderInterface $repositoryReader
     * @param \EmailSender\MessageStore\Domain\Contract\EmailComposerInterface                $emailComposer
     * @param \EmailSender\Recipients\Application\Service\RecipientsService                   $recipientsService
     */
    public function __construct(
        MessageStoreRepositoryReaderInterface $repositoryReader,
        EmailComposerInterface $emailComposer,
        RecipientsService $recipientsService
    ) {
        $this->repositoryReader  = $repositoryReader;
        $this->emailComposer     = $emailComposer;
        $this->recipientsService = $recipientsService;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $messageId
     *
     * @return \EmailSender\MessageStore\Domain\Aggregate\MessageStore
     */
    public function readByMessageId(UnsignedInteger $messageId): MessageStore
    {
        $messageStoreArray    = $this->repositoryReader->readByMessageId($messageId);
        $messageStoreBuilder = new MessageStoreBuilder($this->emailComposer, $this->recipientsService);

        return $messageStoreBuilder->buildMessageStoreFromArray($messageStoreArray);
    }
}
