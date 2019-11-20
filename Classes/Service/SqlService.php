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

    public function getColumnNamesFromResultRows($rows)
    {
        $newColumns = array();

        // if no rows, then skip this exercise
        if (!is_array($rows) || !is_array($rows[0])) return $newColumns;

        foreach($rows[0] as $field => $value)
        {
	    $newColumns[] = $field;
        }

            DebugUtility::debug($newColumns,'newColumns in getColumnNamesFromResultRows');
        return $newColumns;
    }

    public function mergeFieldTypes($columnNames,$fieldtypes)
    {
        $newColumns = array();

        // if no columnNames, then skip this exercise
        if (!is_array($columnNames)) return $newColumns;

        foreach ($columnNames as $column) {

            // name is important
            $newColumns[$column]['name'] = $column;

            // default is TEXT
            $newColumns[$column]['type'] = 'TEXT';

            // if user overrules, use the fieldtype provided by the user
            if ($fieldtypes[$column]) {
                $newColumns[$column]['type'] = $fieldtypes[$column];
            }
        }

            DebugUtility::debug($newColumns,'newColumns in mergeFieldTypes');
        return $newColumns;
    }

    public function insertRow()
    {
        // now execute the query
        $rowsAffected = $this->connection->executeQuery($this->query);

        // Return the inserted values
        return $rowsAffected;
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
