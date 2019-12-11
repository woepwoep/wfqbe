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

    public function getColumnNamesFromResultRows($rows)
    {
        $columnNames = array();

        // if no rows, then skip this exercise
        if (!is_array($rows) || !is_array($rows[0])) return $columnNames;

        foreach($rows[0] as $field => $value)
        {
	    $columnNames[] = $field;
        }

        return $columnNames;
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

    public function deleteRow()
    {
        // now execute the query
        $rowsAffected = $this->connection->executeQuery($this->query);

        // Return the deleted values
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
	case 'image':
	    $newValue = $value['name'];
	    break;
	case 'valuta':
	    $newValue = str_replace(',','.',$value);
	    break;
        }
	//Debug('Convert. type=:'.$type.': value=:'.$value.': newValue=:'.$newValue.':');
        return $newValue;
    }
}
