<?php
namespace RedSeadog\Wfqbe\Service;

use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use \RedSeadog\Wfqbe\Domain\Repository\QueryRepository;

/**
 *  SqlService
 */
class SqlService
{
    protected $connection;
    protected $query;
    protected $rows;


    public function __construct($query)
    {
        $this->query = $query;

        // we need a connection
        $this->connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('fe_users');

    }

    public function getColumnNames()
    {
        // Return the values
        $this->getRows();
        return array_keys($this->rows[0]);
    }

    public function getRows()
    {
        // now execute the query
        $this->rows = $this->connection
            ->executeQuery($this->query)
            ->fetchAll()
        ;

        // Return the values
        return $this->rows;
    }

    public function updateRow()
    {
        // now execute the query
        $rowsAffected = $this->connection->executeQuery($this->query);

        // Return the updated values
        return $rowsAffected;
    }
}
