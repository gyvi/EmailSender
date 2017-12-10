<?php

namespace EmailSender\ComposedEmail\Infrastructure\Persistence;

use Closure;
use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;
use EmailSender\ComposedEmail\Domain\Contract\ComposedEmailRepositoryWriterInterface;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use PDO;
use PDOException;

/**
 * Class ComposedEmailRepositoryWriter
 *
 * @package EmailSender\ComposedEmail
 */
class ComposedEmailRepositoryWriter implements ComposedEmailRepositoryWriterInterface
{
    /**
     * @var \Closure
     */
    private $composedEmailWriterService;

    /**
     * @var \PDO
     */
    private $dbConnection;

    /**
     * ComposedEmailRepositoryWriter constructor.
     *
     * @param \Closure $composedEmailWriterService
     */
    public function __construct(Closure $composedEmailWriterService)
    {
        $this->composedEmailWriterService = $composedEmailWriterService;
    }

    /**
     * @param \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail $composedEmail
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     *
     * @throws \PDOException
     */
    public function add(ComposedEmail $composedEmail): UnsignedInteger
    {
        $pdo = $this->getConnection();

        $sql = '
            INSERT INTO
                `composedEmail` (
                    `' . ComposedEmailRepositoryFieldList::FROM . '`,
                    `' . ComposedEmailRepositoryFieldList::RECIPIENTS . '`,
                    `' . ComposedEmailRepositoryFieldList::EMAIL . '`
                )
            VALUES
                (
                    :' . ComposedEmailRepositoryFieldList::FROM . ',
                    :' . ComposedEmailRepositoryFieldList::RECIPIENTS . ',
                    :' . ComposedEmailRepositoryFieldList::EMAIL . '
                ); 
        ';

        $statement = $pdo->prepare($sql);

        $statement->bindValue(
            ':' . ComposedEmailRepositoryFieldList::FROM,
            $composedEmail->getFrom()->getAddress()->getValue()
        );

        $statement->bindValue(
            ':' . ComposedEmailRepositoryFieldList::RECIPIENTS,
            json_encode($composedEmail->getRecipients())
        );

        $statement->bindValue(
            ':' . ComposedEmailRepositoryFieldList::EMAIL,
            $composedEmail->getEmail()->getValue()
        );

        if (!$statement->execute()) {
            throw new PDOException('Unable write to the database.');
        }

        $composedEmailId = (int)$pdo->lastInsertId();

        return new UnsignedInteger($composedEmailId);
    }

    /**
     * @return \PDO
     */
    private function getConnection(): PDO
    {
        if (!$this->dbConnection) {
            $this->dbConnection = ($this->composedEmailWriterService)();
        }

        return $this->dbConnection;
    }
}
