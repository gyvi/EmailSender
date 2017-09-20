<?php

namespace EmailSender\MessageLog\Infrastructure\Persistence;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MessageLog\Application\Catalog\ListMessageLogsRequestPropertyNames;
use EmailSender\MessageLog\Domain\Contract\MessageLogRepositoryReaderInterface;
use Closure;
use EmailSender\MessageLog\Domain\Entity\ListMessageLogsRequest;
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
     * SQL error message.
     */
    const SQL_ERROR_MESSAGE = 'Unable read from the database.';

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
                    `logged`,
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
                ':' . MessageLogFieldList::MESSAGE_LOG_ID,
                $messageLogId->getValue(),
                PDO::PARAM_INT
            );

            if (!$statement->execute()) {
                throw new PDOException(static::SQL_ERROR_MESSAGE);
            }

            $messageLogArray = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Error($e->getMessage(), $e->getCode(), $e);
        }

        return $messageLogArray;
    }

    /**
     * @param \EmailSender\MessageLog\Domain\Entity\ListMessageLogsRequest $listMessageLogsRequest
     *
     * @return array
     *
     * @throws \Error
     */
    public function listMessageLogs(ListMessageLogsRequest $listMessageLogsRequest): array
    {
        try {
            $pdo = $this->getConnection();

            $rows = $this->getListMessagesLogLimitValue($listMessageLogsRequest);

            $sql = '
                SELECT
                    `messageLogId`,
                    `messageId`,
                    `from`,
                    `recipients`,
                    `subject`,
                    `logged`,
                    `queued`,
                    `sent`,
                    `delay`,
                    `status`,
                    `errorMessage`
                FROM
                    `messageLog`
                ' . $this->listMessageLogsWhereSQL($listMessageLogsRequest) . '
                ORDER BY
                    messageLogId DESC
                ' . $this->getListMessagesLogLimitSQL($listMessageLogsRequest) . '; 
            ';

            $statement = $pdo->prepare($sql);

            if ($listMessageLogsRequest->getFrom()) {
                $statement->bindValue(
                    ':' . MessageLogFieldList::FROM,
                    $listMessageLogsRequest->getFrom()->getAddress()->getValue(),
                    PDO::PARAM_STR
                );
            }

            $statement->bindValue(
                ':' . ListMessageLogsRequestPropertyNames::ROWS,
                $rows,
                PDO::PARAM_INT
            );

            if ($listMessageLogsRequest->getLastMessageId()) {
                $statement->bindValue(
                    ':' . MessageLogFieldList::MESSAGE_ID,
                    $listMessageLogsRequest->getLastMessageId()->getValue(),
                    PDO::PARAM_INT
                );
            }

            if ($listMessageLogsRequest->getPage()) {
                $statement->bindValue(
                    ':' . ListMessageLogsRequestPropertyNames::PAGE,
                    ($listMessageLogsRequest->getPage()->getValue() > 0
                        ? $listMessageLogsRequest->getPage()->getValue() - 1
                        : 0
                    ) * $rows,
                    PDO::PARAM_INT
                );
            }

            if (!$statement->execute()) {
                throw new PDOException(static::SQL_ERROR_MESSAGE);
            }

            $messageLogCollectionArray = $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Error($e->getMessage(), $e->getCode(), $e);
        }

        return $messageLogCollectionArray;
    }

    /**
     * @param \EmailSender\MessageLog\Domain\Entity\ListMessageLogsRequest $listMessageLogsRequest
     *
     * @return string
     */
    private function listMessageLogsWhereSQL(ListMessageLogsRequest $listMessageLogsRequest): string
    {
        $listMessageLogsWhereSQL = '';

        $expressions = [];

        if ($listMessageLogsRequest->getFrom()) {
            $expressions[]= '`from` = :from';
        }

        if ($listMessageLogsRequest->getLastMessageId()) {
            $expressions[]= '`messageId` < :messageId';
        }

        if (!empty($expressions)) {
            $listMessageLogsWhereSQL = '
            WHERE
                ' . implode(PHP_EOL . 'AND ', $expressions);
        }

        return $listMessageLogsWhereSQL;
    }

    /**
     * @param \EmailSender\MessageLog\Domain\Entity\ListMessageLogsRequest $listMessageLogsRequest
     *
     * @return string
     */
    private function getListMessagesLogLimitSQL(ListMessageLogsRequest $listMessageLogsRequest): string
    {
        if ($listMessageLogsRequest->getPage()) {
            $listMessagesLogLimitSQL = 'LIMIT :page, :rows';
        } else {
            $listMessagesLogLimitSQL = 'LIMIT :rows';
        }

        return $listMessagesLogLimitSQL;
    }

    /**
     * @param \EmailSender\MessageLog\Domain\Entity\ListMessageLogsRequest $listMessageLogsRequest
     *
     * @return int
     */
    private function getListMessagesLogLimitValue(ListMessageLogsRequest $listMessageLogsRequest): int
    {
        if ($listMessageLogsRequest->getRows()) {
            $listMessagesLogLimit = $listMessageLogsRequest->getRows()->getValue();
        } else {
            $listMessagesLogLimit = ListMessageLogsRequest::DEFAULT_ROWS;
        }

        return $listMessagesLogLimit;
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
