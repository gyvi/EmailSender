<?php

namespace EmailSender\ComposedEmail\Infrastructure\Persistence;

use Closure;
use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;
use EmailSender\ComposedEmail\Domain\Contract\ComposedEmailRepositoryWriterInterface;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use PDO;
use PDOException;
use Error;

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
     * @throws \Error
     */
    public function add(ComposedEmail $composedEmail): UnsignedInteger
    {
        try {
            $pdo = $this->getConnection();

            $sql = '
                INSERT INTO
                    `composedEmail` (
                        `' . ComposedEmailFieldList::FROM . '`,
                        `' . ComposedEmailFieldList::RECIPIENTS . '`,
                        `' . ComposedEmailFieldList::EMAIL . '`
                    )
                VALUES
                    (
                        :' . ComposedEmailFieldList::FROM . ',
                        :' . ComposedEmailFieldList::RECIPIENTS . ',
                        :' . ComposedEmailFieldList::EMAIL . '
                    ); 
            ';

            $statement = $pdo->prepare($sql);

            $statement->bindValue(
                ':' . ComposedEmailFieldList::FROM,
                $composedEmail->getFrom()->getAddress()->getValue()
            );

            $statement->bindValue(
                ':' . ComposedEmailFieldList::RECIPIENTS,
                json_encode($composedEmail->getRecipients())
            );

            $statement->bindValue(
                ':' . ComposedEmailFieldList::EMAIL,
                $composedEmail->getEmail()->getValue()
            );

            if (!$statement->execute()) {
                throw new PDOException('Unable write to the database.');
            }

            $composedEmailId = (int)$pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Error($e->getMessage(), $e->getCode(), $e);
        }

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
