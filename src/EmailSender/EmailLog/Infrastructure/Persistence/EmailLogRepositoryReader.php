<?php

namespace EmailSender\EmailLog\Infrastructure\Persistence;

use EmailSender\EmailLog\Application\Catalog\EmailLogPropertyNamesList;
use EmailSender\EmailLog\Application\Catalog\ListEmailLogRequestPropertyNameList;
use EmailSender\EmailLog\Application\Collection\EmailLogCollection;
use EmailSender\EmailLog\Domain\Contract\EmailLogRepositoryReaderInterface;
use Closure;
use EmailSender\EmailLog\Domain\Entity\ListEmailLogRequest;
use EmailSender\EmailLog\Domain\Factory\EmailLogCollectionFactory;
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
     * @param \EmailSender\EmailLog\Domain\Factory\EmailLogCollectionFactory $emailLogCollectionFactory
     */
    public function __construct(
        Closure $emailLogReaderService,
        EmailLogCollectionFactory $emailLogCollectionFactory
    ) {
        $this->emailLogReaderService     = $emailLogReaderService;
        $this->emailLogCollectionFactory = $emailLogCollectionFactory;
    }

    /**
     * @param \EmailSender\EmailLog\Domain\Entity\ListEmailLogRequest $listEmailLogRequest
     *
     * @return \EmailSender\EmailLog\Application\Collection\EmailLogCollection
     *
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @throws \InvalidArgumentException
     * @throws \PDOException
     */
    public function list(ListEmailLogRequest $listEmailLogRequest): EmailLogCollection
    {
        $pdo = $this->getConnection();

        $perPage = $this->getListLimitValue($listEmailLogRequest);

        $sql = '
            SELECT
                `emailLogId`      as `' . EmailLogPropertyNamesList::EMAIL_LOG_ID . '`,
                `composedEmailId` as `' . EmailLogPropertyNamesList::COMPOSED_EMAIL_ID . '`,
                `from`            as `' . EmailLogPropertyNamesList::FROM . '`,
                `recipients`      as `' . EmailLogPropertyNamesList::RECIPIENTS . '`,
                `subject`         as `' . EmailLogPropertyNamesList::SUBJECT . '`,
                `logged`          as `' . EmailLogPropertyNamesList::LOGGED . '`,
                `queued`          as `' . EmailLogPropertyNamesList::QUEUED . '`,
                `sent`            as `' . EmailLogPropertyNamesList::SENT . '`,
                `delay`           as `' . EmailLogPropertyNamesList::DELAY . '`,
                `status`          as `' . EmailLogPropertyNamesList::STATUS . '`,
                `errorMessage`    as `' . EmailLogPropertyNamesList::ERROR_MESSAGE . '`
            FROM
                `emailLog`
            ' . $this->getListWhereSQL($listEmailLogRequest) . '
            ORDER BY
                `emailLogId` DESC
            ' . $this->getListLimitSQL($listEmailLogRequest) . '; 
        ';

        $statement = $pdo->prepare($sql);

        if ($listEmailLogRequest->getFrom()) {
            $statement->bindValue(
                ':' . EmailLogPropertyNamesList::FROM,
                $listEmailLogRequest->getFrom()->getAddress()->getValue()
            );
        }

        $statement->bindValue(
            ':' . ListEmailLogRequestPropertyNameList::PER_PAGE,
            $perPage,
            PDO::PARAM_INT
        );

        if ($listEmailLogRequest->getLastComposedEmailId()) {
            $statement->bindValue(
                ':' . EmailLogPropertyNamesList::COMPOSED_EMAIL_ID,
                $listEmailLogRequest->getLastComposedEmailId()->getValue(),
                PDO::PARAM_INT
            );
        }

        if ($listEmailLogRequest->getPage()) {
            $statement->bindValue(
                ':' . ListEmailLogRequestPropertyNameList::PAGE,
                ($listEmailLogRequest->getPage()->getValue() > 0
                    ? $listEmailLogRequest->getPage()->getValue() - 1
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
     * @param \EmailSender\EmailLog\Domain\Entity\ListEmailLogRequest $listEmailLogRequest
     *
     * @return string
     */
    private function getListWhereSQL(ListEmailLogRequest $listEmailLogRequest): string
    {
        $listWhereSQL = '';

        $expressions = [];

        if ($listEmailLogRequest->getFrom()) {
            $expressions[]= '`from` = :' . EmailLogPropertyNamesList::FROM;
        }

        if ($listEmailLogRequest->getLastComposedEmailId()) {
            $expressions[]= '`composedEmailId` < :' . EmailLogPropertyNamesList::COMPOSED_EMAIL_ID;
        }

        if (!empty($expressions)) {
            $listWhereSQL = '
            WHERE
                ' . implode(PHP_EOL . 'AND ', $expressions);
        }

        return $listWhereSQL;
    }

    /**
     * @param \EmailSender\EmailLog\Domain\Entity\ListEmailLogRequest $listEmailLogRequest
     *
     * @return string
     */
    private function getListLimitSQL(ListEmailLogRequest $listEmailLogRequest): string
    {
        $listLimitSQL = 'LIMIT :' . ListEmailLogRequestPropertyNameList::PER_PAGE;

        if ($listEmailLogRequest->getPage()) {
            $listLimitSQL = 'LIMIT :'
                . ListEmailLogRequestPropertyNameList::PAGE . ', :'
                . ListEmailLogRequestPropertyNameList::PER_PAGE;
        }

        return $listLimitSQL;
    }

    /**
     * @param \EmailSender\EmailLog\Domain\Entity\ListEmailLogRequest $listEmailLogRequest
     *
     * @return int
     */
    private function getListLimitValue(ListEmailLogRequest $listEmailLogRequest): int
    {
        $listLimit = ListEmailLogRequest::DEFAULT_PER_PAGE;

        if ($listEmailLogRequest->getPerPage()) {
            $listLimit = $listEmailLogRequest->getPerPage()->getValue();
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
