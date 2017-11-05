<?php

namespace EmailSender\ComposedEmail\Infrastructure\Persistence;

use Closure;
use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;
use EmailSender\ComposedEmail\Domain\Factory\ComposedEmailFactory;
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
     * @var \EmailSender\ComposedEmail\Domain\Factory\ComposedEmailFactory
     */
    private $composedEmailFactory;

    /**
     * @var \PDO
     */
    private $dbConnection;

    /**
     * ComposedEmailRepositoryReader constructor.
     *
     * @param \Closure                                                       $composedEmailReaderService
     * @param \EmailSender\ComposedEmail\Domain\Factory\ComposedEmailFactory $composedEmailFactory
     */
    public function __construct(Closure $composedEmailReaderService, ComposedEmailFactory $composedEmailFactory)
    {
        $this->composedEmailReaderService = $composedEmailReaderService;
        $this->composedEmailFactory       = $composedEmailFactory;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $composedEmailId
     *
     * @return \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail
     *
     * @throws \Error
     * @throws \InvalidArgumentException
     */
    public function get(UnsignedInteger $composedEmailId): ComposedEmail
    {
        try {
            $pdo = $this->getConnection();

            $sql = '
                SELECT
                    `' . ComposedEmailFieldList::COMPOSED_EMAIL_ID . '`,
                    `' . ComposedEmailFieldList::FROM . '`,
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

        return $this->composedEmailFactory->createFromArray($composedEmailArray);
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
