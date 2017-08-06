<?php

namespace EmailSender\MessageStore\Infrastructure\Persistence;

use Closure;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MessageStore\Domain\Contract\MessageStoreRepositoryReaderInterface;
use PDO;
use PDOException;
use Error;

/**
 * Class MessageStoreRepositoryReader
 *
 * @package EmailSender\MessageStore
 */
class MessageStoreRepositoryReader implements MessageStoreRepositoryReaderInterface
{
    /**
     * @var \Closure
     */
    private $messageStoreReaderService;

    /**
     * @var \PDO
     */
    private $dbConnection;

    /**
     * MessageStoreRepositoryReader constructor.
     *
     * @param \Closure $messageStoreReaderService
     */
    public function __construct(Closure $messageStoreReaderService)
    {
        $this->messageStoreReaderService = $messageStoreReaderService;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $messageId
     *
     * @return array
     *
     * @throws \Error
     */
    public function readByMessageId(UnsignedInteger $messageId): array
    {
        try {
            $pdo = $this->getConnection();

            $sql = '
                SELECT
                    messageId,
                    recipients,
                    message
                FROM
                    messageStore
                WHERE
                    messageId = :messageId; 
            ';

            $statement = $pdo->prepare($sql);

            $statement->bindParam(
                ':' . MessageStoreFieldList::MESSAGE_ID_FIELD,
                $messageId->getValue(),
                PDO::PARAM_INT
            );

            $statement->execute();

            $messageStoreArray = $statement->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {

            throw new Error($e->getMessage(), $e->getCode(), $e);
        }

        return $messageStoreArray;
    }

    /**
     * @return \PDO
     */
    private function getConnection(): PDO
    {
        if (!$this->dbConnection) {
            $this->dbConnection = ($this->messageStoreReaderService)();
        }

        return $this->dbConnection;
    }
}
