<?php

namespace EmailSender\ComposedEmail\Infrastructure\Persistence;

use Closure;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\ComposedEmail\Domain\Contract\ComposedEmailRepositoryReaderInterface;
use PDO;
use PDOException;
use Error;

/**
 * Class ComposedEmailRepositoryReader
 *
 * @package EmailSender\ComposedEmail
 */
class ComposedEmailRepositoryReader implements ComposedEmailRepositoryReaderInterface
{
    /**
     * @var \Closure
     */
    private $composedEmailReaderService;

    /**
     * @var \PDO
     */
    private $dbConnection;

    /**
     * ComposedEmailRepositoryReader constructor.
     *
     * @param \Closure $composedEmailReaderService
     */
    public function __construct(Closure $composedEmailReaderService)
    {
        $this->composedEmailReaderService = $composedEmailReaderService;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $composedEmailId
     *
     * @return array
     *
     * @throws \Error
     */
    public function get(UnsignedInteger $composedEmailId): array
    {
        try {
            $pdo = $this->getConnection();

            $sql = '
                SELECT
                    `' . ComposedEmailFieldList::COMPOSED_EMAIL_ID . '`,
                    `' . ComposedEmailFieldList::RECIPIENTS . '`,
                    `' . ComposedEmailFieldList::EMAIL . '`
                FROM
                    `composedEmail`
                WHERE
                    `' . ComposedEmailFieldList::COMPOSED_EMAIL_ID . '` =
                        :' .  ComposedEmailFieldList::COMPOSED_EMAIL_ID . '; 
            ';

            $statement = $pdo->prepare($sql);

            $statement->bindValue(
                ':' . ComposedEmailFieldList::COMPOSED_EMAIL_ID,
                $composedEmailId->getValue(),
                PDO::PARAM_INT
            );

            if (!$statement->execute()) {
                throw new PDOException('Unable read from the database.');
            }

            $composedEmailArray = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Error($e->getMessage(), $e->getCode(), $e);
        }

        return $composedEmailArray;
    }

    /**
     * @return \PDO
     */
    private function getConnection(): PDO
    {
        if (!$this->dbConnection) {
            $this->dbConnection = ($this->composedEmailReaderService)();
        }

        return $this->dbConnection;
    }
}
