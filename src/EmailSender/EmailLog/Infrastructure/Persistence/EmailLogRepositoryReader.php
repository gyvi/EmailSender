<?php

namespace EmailSender\EmailLog\Infrastructure\Persistence;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\EmailLog\Application\Catalog\ListRequestPropertyNames;
use EmailSender\EmailLog\Application\Collection\EmailLogCollection;
use EmailSender\EmailLog\Domain\Aggregate\EmailLog;
use EmailSender\EmailLog\Domain\Contract\EmailLogRepositoryReaderInterface;
use Closure;
use EmailSender\EmailLog\Domain\Entity\ListRequest;
use EmailSender\EmailLog\Domain\Factory\EmailLogCollectionFactory;
use EmailSender\EmailLog\Domain\Factory\EmailLogFactory;
use PDO;
use PDOException;

/**
 * Class EmailLogRepositoryReader
 *
 * @package EmailSender\EmailLog
 */
class EmailLogRepositoryReader implements EmailLogRepositoryReaderInterface
{
    /**
     * SQL error message.
     */
    const SQL_ERROR_MESSAGE = 'Unable read from the database.';

    /**
     * @var \Closure
     */
    private $emailLogReaderService;

    /**
     * @var \EmailSender\EmailLog\Domain\Factory\EmailLogFactory
     */
    private $emailLogFactory;

    /**
     * @var \EmailSender\EmailLog\Domain\Factory\EmailLogCollectionFactory
     */
    private $emailLogCollectionFactory;

    /**
     * @var \PDO
     */
    private $dbConnection;

    /**
     * EmailLogRepositoryReader constructor.
     *
     * @param \Closure                                                       $emailLogReaderService
     * @param \EmailSender\EmailLog\Domain\Factory\EmailLogFactory           $emailLogFactory
     * @param \EmailSender\EmailLog\Domain\Factory\EmailLogCollectionFactory $emailLogCollectionFactory
     */
    public function __construct(
        Closure $emailLogReaderService,
        EmailLogFactory $emailLogFactory,
        EmailLogCollectionFactory $emailLogCollectionFactory
    ) {
        $this->emailLogReaderService     = $emailLogReaderService;
        $this->emailLogFactory           = $emailLogFactory;
        $this->emailLogCollectionFactory = $emailLogCollectionFactory;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $emailLogId
     *
     * @return \EmailSender\EmailLog\Domain\Aggregate\EmailLog
     *
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @throws \InvalidArgumentException
     * @throws \PDOException
     */
    public function get(UnsignedInteger $emailLogId): EmailLog
    {
        $pdo = $this->getConnection();

        $sql = '
            SELECT
                `' . EmailLogFieldList::EMAIL_LOG_ID . '`,
                `' . EmailLogFieldList::COMPOSED_EMAIL_ID . '`,
                `' . EmailLogFieldList::FROM . '`,
                `' . EmailLogFieldList::RECIPIENTS . '`,
                `' . EmailLogFieldList::SUBJECT . '`,
                `' . EmailLogFieldList::LOGGED . '`,
                `' . EmailLogFieldList::QUEUED . '`,
                `' . EmailLogFieldList::SENT . '`,
                `' . EmailLogFieldList::DELAY . '`,
                `' . EmailLogFieldList::STATUS . '`,
                `' . EmailLogFieldList::ERROR_MESSAGE . '`
            FROM
                `emailLog`
            WHERE
                `' .  EmailLogFieldList::EMAIL_LOG_ID . '` = :' .  EmailLogFieldList::EMAIL_LOG_ID . '; 
        ';

        $statement = $pdo->prepare($sql);

        $statement->bindValue(':' . EmailLogFieldList::EMAIL_LOG_ID, $emailLogId->getValue(), PDO::PARAM_INT);

        if (!$statement->execute()) {
            throw new PDOException(static::SQL_ERROR_MESSAGE);
        }

        return $this->emailLogFactory->createFromArray($statement->fetch(PDO::FETCH_ASSOC));
    }

    /**
     * @param \EmailSender\EmailLog\Domain\Entity\ListRequest $listRequest
     *
     * @return \EmailSender\EmailLog\Application\Collection\EmailLogCollection
     *
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @throws \InvalidArgumentException
     * @throws \PDOException
     */
    public function list(ListRequest $listRequest): EmailLogCollection
    {
        $pdo = $this->getConnection();

        $perPage = $this->getListLimitValue($listRequest);

        $sql = '
            SELECT
                `' . EmailLogFieldList::EMAIL_LOG_ID . '`,
                `' . EmailLogFieldList::COMPOSED_EMAIL_ID . '`,
                `' . EmailLogFieldList::FROM . '`,
                `' . EmailLogFieldList::RECIPIENTS . '`,
                `' . EmailLogFieldList::SUBJECT . '`,
                `' . EmailLogFieldList::LOGGED . '`,
                `' . EmailLogFieldList::QUEUED . '`,
                `' . EmailLogFieldList::SENT . '`,
                `' . EmailLogFieldList::DELAY . '`,
                `' . EmailLogFieldList::STATUS . '`,
                `' . EmailLogFieldList::ERROR_MESSAGE . '`
            FROM
                `emailLog`
            ' . $this->getListWhereSQL($listRequest) . '
            ORDER BY
                `' . EmailLogFieldList::EMAIL_LOG_ID . '` DESC
            ' . $this->getListLimitSQL($listRequest) . '; 
        ';

        $statement = $pdo->prepare($sql);

        if ($listRequest->getFrom()) {
            $statement->bindValue(
                ':' . EmailLogFieldList::FROM,
                $listRequest->getFrom()->getAddress()->getValue()
            );
        }

        $statement->bindValue(
            ':' . ListRequestPropertyNames::PER_PAGE,
            $perPage,
            PDO::PARAM_INT
        );

        if ($listRequest->getLastComposedEmailId()) {
            $statement->bindValue(
                ':' . EmailLogFieldList::COMPOSED_EMAIL_ID,
                $listRequest->getLastComposedEmailId()->getValue(),
                PDO::PARAM_INT
            );
        }

        if ($listRequest->getPage()) {
            $statement->bindValue(
                ':' . ListRequestPropertyNames::PAGE,
                ($listRequest->getPage()->getValue() > 0
                    ? $listRequest->getPage()->getValue() - 1
                    : 0
                ) * $perPage,
                PDO::PARAM_INT
            );
        }

        if (!$statement->execute()) {
            throw new PDOException(static::SQL_ERROR_MESSAGE);
        }

        return $this->emailLogCollectionFactory->create($statement->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * @param \EmailSender\EmailLog\Domain\Entity\ListRequest $listRequest
     *
     * @return string
     */
    private function getListWhereSQL(ListRequest $listRequest): string
    {
        $listWhereSQL = '';

        $expressions = [];

        if ($listRequest->getFrom()) {
            $expressions[]= '`' . EmailLogFieldList::FROM . '` = :' . EmailLogFieldList::FROM;
        }

        if ($listRequest->getLastComposedEmailId()) {
            $expressions[]= '`' . EmailLogFieldList::COMPOSED_EMAIL_ID . '` < :' . EmailLogFieldList::COMPOSED_EMAIL_ID;
        }

        if (!empty($expressions)) {
            $listWhereSQL = '
            WHERE
                ' . implode(PHP_EOL . 'AND ', $expressions);
        }

        return $listWhereSQL;
    }

    /**
     * @param \EmailSender\EmailLog\Domain\Entity\ListRequest $listRequest
     *
     * @return string
     */
    private function getListLimitSQL(ListRequest $listRequest): string
    {
        $listLimitSQL = 'LIMIT :' . ListRequestPropertyNames::PER_PAGE;

        if ($listRequest->getPage()) {
            $listLimitSQL = 'LIMIT :' . ListRequestPropertyNames::PAGE . ', :' . ListRequestPropertyNames::PER_PAGE;
        }

        return $listLimitSQL;
    }

    /**
     * @param \EmailSender\EmailLog\Domain\Entity\ListRequest $listRequest
     *
     * @return int
     */
    private function getListLimitValue(ListRequest $listRequest): int
    {
        $listLimit = ListRequest::DEFAULT_PER_PAGE;

        if ($listRequest->getPerPage()) {
            $listLimit = $listRequest->getPerPage()->getValue();
        }

        return $listLimit;
    }

    /**
     * @return \PDO
     */
    private function getConnection(): PDO
    {
        if (!$this->dbConnection) {
            $this->dbConnection = ($this->emailLogReaderService)();
        }

        return $this->dbConnection;
    }
}
