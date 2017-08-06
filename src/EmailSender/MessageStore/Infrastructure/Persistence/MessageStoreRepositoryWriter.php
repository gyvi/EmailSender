<?php

namespace EmailSender\MessageStore\Infrastructure\Persistence;

use Closure;
use EmailSender\MessageStore\Domain\Aggregate\MessageStore;
use EmailSender\MessageStore\Domain\Contract\MessageStoreRepositoryWriterInterface;
use PDO;
use PDOException;
use Error;

/**
 * Class MessageStoreRepositoryWriter
 *
 * @package EmailSender\MessageStore
 */
class MessageStoreRepositoryWriter implements MessageStoreRepositoryWriterInterface
{
    /**
     * @var \Closure
     */
    private $messageStoreWriterService;

    /**
     * @var \PDO
     */
    private $dbConnection;

    /**
     * MessageStoreRepositoryWriter constructor.
     *
     * @param \Closure $messageStoreWriterService
     */
    public function __construct(Closure $messageStoreWriterService)
    {
        $this->messageStoreWriterService = $messageStoreWriterService;
    }

    /**
     * @param \EmailSender\MessageStore\Domain\Aggregate\MessageStore $messageStore
     *
     * @return int
     *
     * @throws \Error
     */
    public function add(MessageStore $messageStore): int
    {
        try {
            $pdo = $this->getConnection();

            $sql = '
                INSERT INTO
                    `messageStore` (`recipients`, `message`)
                VALUES
                    (:recipients, :message); 
            ';

            $statement = $pdo->prepare($sql);

            $statement->bindParam(
                ':' . MessageStoreFieldList::FIELD_RECIPIENTS,
                json_encode($messageStore->getRecipients()),
                PDO::PARAM_STR
            );

            $statement->bindParam(
                ':' . MessageStoreFieldList::FIELD_MESSAGE,
                $messageStore->getMessage()->getValue(),
                PDO::PARAM_STR
            );

            if (!$statement->execute()) {
                throw new PDOException('Unable write to the database.');
            }

            $messageId = (int)$pdo->lastInsertId();

        } catch (PDOException $e) {

            throw new Error($e->getMessage(), $e->getCode(), $e);
        }

        return $messageId;
    }

    /**
     * @return \PDO
     */
    private function getConnection(): PDO
    {
        if (!$this->dbConnection) {
            $this->dbConnection = ($this->messageStoreWriterService)();
        }

        return $this->dbConnection;
    }
}
