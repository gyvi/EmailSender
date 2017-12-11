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
                    `' . EmailLogRepositoryFieldList::COMPOSED_EMAIL_ID . '`,
                    `' . EmailLogRepositoryFieldList::FROM . '`,
                    `' . EmailLogRepositoryFieldList::RECIPIENTS . '`,
                    `' . EmailLogRepositoryFieldList::SUBJECT . '`,
                    `' . EmailLogRepositoryFieldList::DELAY . '`
                )
            VALUES
                (
                    :' . EmailLogRepositoryFieldList::COMPOSED_EMAIL_ID . ',
                    :' . EmailLogRepositoryFieldList::FROM . ',
                    :' . EmailLogRepositoryFieldList::RECIPIENTS . ',
                    :' . EmailLogRepositoryFieldList::SUBJECT . ',
                    :' . EmailLogRepositoryFieldList::DELAY . '
                ); 
        ';

        $statement = $pdo->prepare($sql);

        $statement->bindValue(
            ':' . EmailLogRepositoryFieldList::COMPOSED_EMAIL_ID,
            $emailLog->getComposedEmailId()->getValue(),
            PDO::PARAM_INT
        );

        $statement->bindValue(':' . EmailLogRepositoryFieldList::FROM, $emailLog->getFrom()->getAddress()->getValue());

        $statement->bindValue(':' . EmailLogRepositoryFieldList::RECIPIENTS, json_encode($emailLog->getRecipients()));

        $statement->bindValue(':' . EmailLogRepositoryFieldList::SUBJECT, $emailLog->getSubject()->getValue());

        $statement->bindValue(
            ':' . EmailLogRepositoryFieldList::DELAY,
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
     * @param \EmailSender\Core\ValueObject\EmailStatus                                $emailStatus
     * @param \EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral    $errorMessage
     *
     * @throws \PDOException
     */
    public function setStatus(
        UnsignedInteger $emailLogId,
        EmailStatus $emailStatus,
        StringLiteral $errorMessage
    ): void {
        $pdo = $this->getConnection();

        $sql = '
            UPDATE
                `emailLog`
            SET
                `' . EmailLogRepositoryFieldList::STATUS . '` = :' . EmailLogRepositoryFieldList::STATUS
            . $this->getSetStatusDateTimeUpdate($emailStatus)
            . $this->getSetStatusErrorMessageUpdate($emailStatus)
            . '
            WHERE
                `' . EmailLogRepositoryFieldList::EMAIL_LOG_ID .
                '` = :' .  EmailLogRepositoryFieldList::EMAIL_LOG_ID . ';
        ';

        $statement = $pdo->prepare($sql);

        $statement->bindValue(':' . EmailLogRepositoryFieldList::STATUS, $emailStatus->getValue());

        $statement->bindValue(
            ':' . EmailLogRepositoryFieldList::EMAIL_LOG_ID,
            $emailLogId->getValue(),
            PDO::PARAM_INT
        );

        if ($emailStatus->getValue() === EmailStatusList::STATUS_ERROR) {
            $statement->bindValue(':' . EmailLogRepositoryFieldList::ERROR_MESSAGE, $errorMessage->getValue());
        }

        if (!$statement->execute()) {
            throw new PDOException('Unable to write to the database.');
        }
    }

    /**
     * @param \EmailSender\Core\ValueObject\EmailStatus $emailStatus
     *
     * @return string
     */
    private function getSetStatusDateTimeUpdate(EmailStatus $emailStatus): string
    {
        switch ($emailStatus->getValue()) {
            case EmailStatusList::STATUS_QUEUED:
                $sqlDateTimeUpdate = ',
                    `' . EmailLogRepositoryFieldList::QUEUED . '` = NOW() 
                ';
                break;

            case EmailStatusList::STATUS_SENT:
                $sqlDateTimeUpdate = ',
                    `' . EmailLogRepositoryFieldList::SENT . '` = NOW()
                ';
                break;

            default:
                $sqlDateTimeUpdate = '';
                break;
        }

        return $sqlDateTimeUpdate;
    }

    /**
     * @param \EmailSender\Core\ValueObject\EmailStatus $emailStatus
     *
     * @return string
     */
    private function getSetStatusErrorMessageUpdate(EmailStatus $emailStatus): string
    {
        $sqlDateTimeUpdate = '';

        if ($emailStatus->getValue() === EmailStatusList::STATUS_ERROR) {
            $sqlDateTimeUpdate = ',
                    `' . EmailLogRepositoryFieldList::ERROR_MESSAGE .
                    '` = :' . EmailLogRepositoryFieldList::ERROR_MESSAGE . '
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
