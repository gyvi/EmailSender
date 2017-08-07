<?php

namespace EmailSender\MessageLog\Infrastructure\Persistence;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MessageLog\Domain\Contract\MessageLogRepositoryReaderInterface;
use Closure;
use PDO;
use PDOException;
use Error;

/**
 * Class MessageLogRepositoryReader
 *
 * @package EmailSender\MessageLog
 */
class MessageLogRepositoryReader implements MessageLogRepositoryReaderInterface
{
    /**
     * @var \Closure
     */
    private $messageLogReaderService;

    /**
     * @var \PDO
     */
    private $dbConnection;

    /**
     * MessageLogRepositoryReader constructor.
     *
     * @param \Closure $messageLogReaderService
     */
    public function __construct(Closure $messageLogReaderService)
    {
        $this->messageLogReaderService = $messageLogReaderService;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $messageLogId
     *
     * @return array
     *
     * @throws \Error
     */
    public function readByMessageLogId(UnsignedInteger $messageLogId): array
    {
        try {
            $pdo = $this->getConnection();

            $sql = '
                SELECT
                    `messageLogId`,
                    `messageId`,
                    `from`,
                    `recipients`,
                    `subject`,
                    `queued`,
                    `sent`,
                    `delay`,
                    `status`,
                    `errorMessage`
                FROM
                    `messageLog`
                WHERE
                    `messageLogId` = :messageLogId; 
            ';

            $statement = $pdo->prepare($sql);

            $statement->bindValue(
                ':' . MessageLogFieldList::FIELD_MESSAGE_LOG_ID,
                $messageLogId->getValue(),
                PDO::PARAM_INT
            );

            if (!$statement->execute()) {
                throw new PDOException('Unable read from the database.');
            }

            $messageLogArray = $statement->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {

            throw new Error($e->getMessage(), $e->getCode(), $e);
        }

        return $messageLogArray;
    }

    /**
     * @return \PDO
     */
    private function getConnection(): PDO
    {
        if (!$this->dbConnection) {
            $this->dbConnection = ($this->messageLogReaderService)();
        }

        return $this->dbConnection;
    }
}
