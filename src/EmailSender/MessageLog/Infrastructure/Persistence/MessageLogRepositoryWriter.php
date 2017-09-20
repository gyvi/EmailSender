<?php

namespace EmailSender\MessageLog\Infrastructure\Persistence;

use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
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
                ':' . MessageLogFieldList::MESSAGE_ID,
                $messageLog->getMessageId()->getValue(),
                PDO::PARAM_INT
            );

            $statement->bindValue(
                ':' . MessageLogFieldList::FROM,
                $messageLog->getFrom()->getAddress()->getValue(),
                PDO::PARAM_STR
            );

            $statement->bindValue(
                ':' . MessageLogFieldList::RECIPIENTS,
                json_encode($messageLog->getRecipients()),
                PDO::PARAM_STR
            );

            $statement->bindValue(
                ':' . MessageLogFieldList::SUBJECT,
                $messageLog->getSubject()->getValue(),
                PDO::PARAM_STR
            );

            $statement->bindValue(
                ':' . MessageLogFieldList::DELAY,
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
     * @param \EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral    $errorMessage
     *
     * @throws \Error
     */
    public function setStatus(
        UnsignedInteger $messageLogId,
        MessageLogStatus $messageLogStatus,
        StringLiteral $errorMessage
    ): void {
        try {
            $pdo = $this->getConnection();

            $sql = '
                UPDATE
                    `messageLog`
                SET
                    `status` = :status'
                . $this->getSetStatusDateTimeUpdate($messageLogStatus)
                . $this->getSetStatusErrorMessageUpdate($messageLogStatus)
                . '
                WHERE
                    `messageLogId` = :messageLogId
            ';

            $statement = $pdo->prepare($sql);

            $statement->bindValue(
                ':' . MessageLogFieldList::STATUS,
                $messageLogStatus->getValue(),
                PDO::PARAM_STR
            );

            $statement->bindValue(
                ':' . MessageLogFieldList::MESSAGE_LOG_ID,
                $messageLogId->getValue(),
                PDO::PARAM_INT
            );

            if ($messageLogStatus->getValue() === MessageLogStatuses::STATUS_ERROR) {
                $statement->bindValue(
                    ':' . MessageLogFieldList::ERROR_MESSAGE,
                    $errorMessage->getValue(),
                    PDO::PARAM_STR
                );
            }

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
     */
    private function getSetStatusDateTimeUpdate(MessageLogStatus $messageLogStatus): string
    {
        switch ($messageLogStatus->getValue()) {
            case MessageLogStatuses::STATUS_QUEUED:
                $sqlDateTimeUpdate = ',
                    `queued` = NOW() 
                ';
                break;

            case MessageLogStatuses::STATUS_SENT:
                $sqlDateTimeUpdate = ',
                    `sent` = NOW()
                ';
                break;

            default:
                $sqlDateTimeUpdate = '';
                break;
        }

        return $sqlDateTimeUpdate;
    }

    /**
     * @param \EmailSender\MessageLog\Application\ValueObject\MessageLogStatus $messageLogStatus
     *
     * @return string
     */
    private function getSetStatusErrorMessageUpdate(MessageLogStatus $messageLogStatus): string
    {
        switch ($messageLogStatus->getValue()) {
            case MessageLogStatuses::STATUS_ERROR:
                $sqlDateTimeUpdate = ',
                    `errorMessage` = :errorMessage
                ';
                break;

            default:
                $sqlDateTimeUpdate = '';
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
