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
if (empty($this->rows[0])) {
            DebugUtility::debug($this->query,'query in getColumnNames');exit(1);
}
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

    public function getNewColumns($columnNames,$rows,$fieldtypes)
    {
        $newColumns = array();
        $i=0;
        foreach ($columnNames as $column) {

            // name is important
            $newColumns[$column]['name'] = $column;

            // default is TEXT
            $newColumns[$column]['type'] = 'TEXT';

            // if no rows, then skip this exercise
            if (!is_array($rows)) continue;
            
            // if numeric, default is NUMERIC
            if (is_numeric($rows[0][$column])) {
                $newColumns[$column]['type'] = 'NUMERIC';
            }

            // if user overrules, use the fieldtype provided by the user
            if ($fieldtypes[$column]) {
                $newColumns[$column]['type'] = $fieldtypes[$column];
            }
            $i++;
        }
        return $newColumns;
    }

    public function updateRow()
    {
        // now execute the query
        $rowsAffected = $this->connection->executeQuery($this->query);

        // Return the updated values
        return $rowsAffected;
    }

    public function convert($type,$value)
    {
        switch($type) {
        default:
            $newValue = $value;
            break;
        case 'datum':
        case 'tijd':
            $newValue = strtotime($value);
            break;
        }
        return $newValue;
    }
}
