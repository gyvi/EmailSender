<?php

namespace EmailSender\EmailLog\Infrastructure\Persistence;

use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\Core\Catalog\EmailStatusList;
use EmailSender\EmailLog\Domain\Aggregate\EmailLog;
use EmailSender\EmailLog\Domain\Contract\EmailLogRepositoryWriterInterface;
use Closure;
use PDO;
use PDOException;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\ValueObject\EmailStatus;

/**
 * Class EmailLogRepositoryWriter
 *
 * @package EmailSender\EmailLog
 */
class EmailLogRepositoryWriter implements EmailLogRepositoryWriterInterface
{
    /**
     * @var \Closure
     */
    private $emailLogWriterService;

    /**
     * @var \PDO
     */
    private $dbConnection;

    /**
     * EmailLogRepositoryWriter constructor.
     *
     * @param \Closure $emailLogWriterService
     */
    public function __construct(Closure $emailLogWriterService)
    {
        $this->emailLogWriterService = $emailLogWriterService;
    }

    /**
     * @param \EmailSender\EmailLog\Domain\Aggregate\EmailLog $emailLog
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     *
     * @throws \PDOException
     */
    public function add(EmailLog $emailLog): UnsignedInteger
    {
        $pdo = $this->getConnection();

        $sql = '
            INSERT INTO
                `emailLog` (
                    `' . EmailLogFieldList::COMPOSED_EMAIL_ID . '`,
                    `' . EmailLogFieldList::FROM . '`,
                    `' . EmailLogFieldList::RECIPIENTS . '`,
                    `' . EmailLogFieldList::SUBJECT . '`,
                    `' . EmailLogFieldList::DELAY . '`
                )
            VALUES
                (
                    :' . EmailLogFieldList::COMPOSED_EMAIL_ID . ',
                    :' . EmailLogFieldList::FROM . ',
                    :' . EmailLogFieldList::RECIPIENTS . ',
                    :' . EmailLogFieldList::SUBJECT . ',
                    :' . EmailLogFieldList::DELAY . '
                ); 
        ';

        $statement = $pdo->prepare($sql);

        $statement->bindValue(
            ':' . EmailLogFieldList::COMPOSED_EMAIL_ID,
            $emailLog->getComposedEmailId()->getValue(),
            PDO::PARAM_INT
        );

        $statement->bindValue(':' . EmailLogFieldList::FROM, $emailLog->getFrom()->getAddress()->getValue());

        $statement->bindValue(':' . EmailLogFieldList::RECIPIENTS, json_encode($emailLog->getRecipients()));

        $statement->bindValue(':' . EmailLogFieldList::SUBJECT, $emailLog->getSubject()->getValue());

        $statement->bindValue(
            ':' . EmailLogFieldList::DELAY,
            $emailLog->getDelay()->getValue(),
            PDO::PARAM_INT
        );

        if (!$statement->execute()) {
            throw new PDOException('Unable to write to the database.');
        }

        return new UnsignedInteger((int)$pdo->lastInsertId());
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $emailLogId
     * @param \EmailSender\Core\ValueObject\EmailStatus                                $emailLogStatus
     * @param \EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral    $errorMessage
     *
     * @throws \PDOException
     */
    public function setStatus(
        UnsignedInteger $emailLogId,
        EmailStatus $emailLogStatus,
        StringLiteral $errorMessage
    ): void {
        $pdo = $this->getConnection();

        $sql = '
            UPDATE
                `emailLog`
            SET
                `' . EmailLogFieldList::STATUS . '` = :' . EmailLogFieldList::STATUS
            . $this->getSetStatusDateTimeUpdate($emailLogStatus)
            . $this->getSetStatusErrorMessageUpdate($emailLogStatus)
            . '
            WHERE
                `' . EmailLogFieldList::EMAIL_LOG_ID . '` = :' .  EmailLogFieldList::EMAIL_LOG_ID . ';
        ';

        $statement = $pdo->prepare($sql);

        $statement->bindValue(':' . EmailLogFieldList::STATUS, $emailLogStatus->getValue());

        $statement->bindValue(
            ':' . EmailLogFieldList::EMAIL_LOG_ID,
            $emailLogId->getValue(),
            PDO::PARAM_INT
        );

        if ($emailLogStatus->getValue() === EmailStatusList::STATUS_ERROR) {
            $statement->bindValue(':' . EmailLogFieldList::ERROR_MESSAGE, $errorMessage->getValue());
        }

        if (!$statement->execute()) {
            throw new PDOException('Unable to write to the database.');
        }
    }

    /**
     * @param \EmailSender\Core\ValueObject\EmailStatus $emailLogStatus
     *
     * @return string
     */
    private function getSetStatusDateTimeUpdate(EmailStatus $emailLogStatus): string
    {
        switch ($emailLogStatus->getValue()) {
            case EmailStatusList::STATUS_QUEUED:
                $sqlDateTimeUpdate = ',
                    `' . EmailLogFieldList::QUEUED . '` = NOW() 
                ';
                break;

            case EmailStatusList::STATUS_SENT:
                $sqlDateTimeUpdate = ',
                    `' . EmailLogFieldList::SENT . '` = NOW()
                ';
                break;

            default:
                $sqlDateTimeUpdate = '';
                break;
        }

        return $sqlDateTimeUpdate;
    }

    /**
     * @param \EmailSender\Core\ValueObject\EmailStatus $emailLogStatus
     *
     * @return string
     */
    private function getSetStatusErrorMessageUpdate(EmailStatus $emailLogStatus): string
    {
        $sqlDateTimeUpdate = '';

        if ($emailLogStatus->getValue() === EmailStatusList::STATUS_ERROR) {
            $sqlDateTimeUpdate = ',
                    `' . EmailLogFieldList::ERROR_MESSAGE . '` = :' . EmailLogFieldList::ERROR_MESSAGE . '
                ';
        }

        return $sqlDateTimeUpdate;
    }

    /**
     * @return \PDO
     */
    private function getConnection(): PDO
    {
        if (!$this->dbConnection) {
            $this->dbConnection = ($this->emailLogWriterService)();
        }

        return $this->dbConnection;
    }
}
