<?php

namespace EmailSender\ComposedEmail\Infrastructure\Persistence;

use Closure;
use EmailSender\ComposedEmail\Application\Catalog\ComposedEmailPropertyNameList;
use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;
use EmailSender\ComposedEmail\Domain\Factory\ComposedEmailFactory;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\ComposedEmail\Domain\Contract\ComposedEmailRepositoryReaderInterface;
use PDO;
use PDOException;

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
     * @throws \InvalidArgumentException
     * @throws \PDOException
     */
    public function get(UnsignedInteger $composedEmailId): ComposedEmail
    {
        $pdo = $this->getConnection();

        $sql = '
            SELECT
                `composedEmailId` as `' . ComposedEmailPropertyNameList::COMPOSED_EMAIL_ID . '`,
                `from`            as `' . ComposedEmailPropertyNameList::FROM . '`,
                `recipients`      as `' . ComposedEmailPropertyNameList::RECIPIENTS . '`,
                `email`           as `' . ComposedEmailPropertyNameList::EMAIL . '`,
            FROM
                `composedEmail`
            WHERE
                `composedEmailId` = :' . ComposedEmailPropertyNameList::COMPOSED_EMAIL_ID . '; 
        ';

        $statement = $pdo->prepare($sql);

        $statement->bindValue(
            ':' . ComposedEmailPropertyNameList::COMPOSED_EMAIL_ID,
            $composedEmailId->getValue(),
            PDO::PARAM_INT
        );

        if (!$statement->execute()) {
            throw new PDOException('Unable read from the database.');
        }

        return $this->composedEmailFactory->createFromArray($statement->fetch(PDO::FETCH_ASSOC));
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
