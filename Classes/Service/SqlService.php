<?php
namespace RedSeadog\Wfqbe\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

/**
 *  SqlService
 */
class SqlService extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    protected $rows;


    public function __construct($query)
    {
        // we need a connection
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('fe_users');

        // now execute the query
        $this->rows = $connection
            ->executeQuery($query)
            ->fetchAll();
    }

    public function getColumnNames()
    {
        // Return the values
        return array_keys($this->rows[0]);
    }

    public function getColumnTypes()
    {
        // Return the values
        return array_keys($this->rows[0]);
    }

    public function getRows()
    {
        // Return the values
        return $this->rows;
    }

}
