<?php

namespace EmailSender\MessageLog\Infrastructure\Persistence;

use EmailSender\MessageLog\Application\Catalog\MessageLogStatuses;
use EmailSender\MessageLog\Domain\Aggregate\MessageLog;
use EmailSender\MessageLog\Domain\Contract\MessageLogRepositoryWriterInterface;
use Closure;
use PDO;
use PDOException;
use Error;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MessageLog\Application\ValueObject\MessageLogStatus;

/**
 * Class MessageLogRepositoryWriter
 *
 * @package EmailSender\MessageLog
 */
class MessageLogRepositoryWriter implements MessageLogRepositoryWriterInterface
{
    /**
     * @var \Closure
     */
    private $messageLogWriterService;

    /**
     * @var \PDO
     */
    private $dbConnection;

    /**
     * MessageLogRepositoryWriter constructor.
     *
     * @param \Closure $messageLogWriterService
     */
    public function __construct(Closure $messageLogWriterService)
    {
        $this->messageLogWriterService = $messageLogWriterService;
    }

    /**
     * @param \EmailSender\MessageLog\Domain\Aggregate\MessageLog $messageLog
     *
     * @return int
     *
     * @throws \Error
     */
    public function add(MessageLog $messageLog): int
    {
        try {
            $pdo = $this->getConnection();

            $sql = '
                INSERT INTO
                    `messageLog` (`messageId`, `from`, `recipients`, `subject`, `delay`)
                VALUES
                    (:messageId, :from, :recipients, :subject, :delay); 
            ';

            $statement = $pdo->prepare($sql);

            $statement->bindValue(
                ':' . MessageLogFieldList::FIELD_MESSAGE_ID,
                $messageLog->getMessageId()->getValue(),
                PDO::PARAM_INT
            );

            $statement->bindValue(
                ':' . MessageLogFieldList::FIELD_FROM,
                $messageLog->getFrom()->getAddress()->getValue(),
                PDO::PARAM_STR
            );

            $statement->bindValue(
                ':' . MessageLogFieldList::FIELD_RECIPIENTS,
                json_encode($messageLog->getRecipients()),
                PDO::PARAM_STR
            );

            $statement->bindValue(
                ':' . MessageLogFieldList::FIELD_SUBJECT,
                $messageLog->getSubject()->getValue(),
                PDO::PARAM_STR
            );

            $statement->bindValue(
                ':' . MessageLogFieldList::FIELD_DELAY,
                $messageLog->getDelay()->getValue(),
                PDO::PARAM_INT
            );

            if (!$statement->execute()) {
                throw new PDOException('Unable to write to the database.');
            }

            $messageLogId = (int)$pdo->lastInsertId();

        } catch (PDOException $e) {

            throw new Error($e->getMessage(), $e->getCode(), $e);
        }

        return $messageLogId;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $messageLogId
     * @param \EmailSender\MessageLog\Application\ValueObject\MessageLogStatus         $messageLogStatus
     *
     * @throws \Error
     */
    public function setStatus(UnsignedInteger $messageLogId, MessageLogStatus $messageLogStatus): void
    {
        try {
            $pdo = $this->getConnection();

            $sql = '
                UPDATE
                    `messageLog`
                SET
                    `status` = :status' . $this->getSetStatusDateTimeUpdate($messageLogStatus) . '
                WHERE
                    `messageLogId` = :messageLogId
            ';

            $statement = $pdo->prepare($sql);

            $statement->bindValue(
                ':' . MessageLogFieldList::FIELD_STATUS,
                $messageLogStatus->getValue(),
                PDO::PARAM_STR
            );

            $statement->bindValue(
                ':' . MessageLogFieldList::FIELD_MESSAGE_LOG_ID,
                $messageLogId->getValue(),
                PDO::PARAM_INT
            );

            if (!$statement->execute()) {
                throw new PDOException('Unable to write to the database.');
            }
        } catch (PDOException $e) {

            throw new Error($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param \EmailSender\MessageLog\Application\ValueObject\MessageLogStatus $messageLogStatus
     *
     * @return string
     *
     * @throws \Error
     */
    private function getSetStatusDateTimeUpdate(MessageLogStatus $messageLogStatus): string
    {
        $sqlDateTimeUpdate = '';

        switch ($messageLogStatus->getValue()) {
            case MessageLogStatuses::STATUS_QUEUED:
                $sqlDateTimeUpdate = ',
                    `queued` = NOW() 
                ';
                break;

            case MessageLogStatuses::STATUS_SENT:
                $sqlDateTimeUpdate = '
                    `sent` = NOW()
                ';
                break;
        }

        return $sqlDateTimeUpdate;
    }

    /**
     * @return \PDO
     */
    private function getConnection(): PDO
    {
        if (!$this->dbConnection) {
            $this->dbConnection = ($this->messageLogWriterService)();
        }

        return $this->dbConnection;
    }
}
