<?php
namespace RedSeadog\Wfqbe\Service;

use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use \RedSeadog\Wfqbe\Domain\Repository\QueryRepository;
use \RedSeadog\Wfqbe\Service\SqlService;

/**
 *  RowsService
 */
class RowsService extends \RedSeadog\Wfqbe\Service\SqlService
{
    /**
     * @var \RedSeadog\Wfqbe\Service\SqlService
     */
    protected $sqlService;

    /**
     * array @rows
     */
    protected $rows;


    public function __construct(\RedSeadog\Wfqbe\Domain\Model\Query $query)
    {
        $this->sqlService = new SqlService($query->getQuery());
    }

    public function getRows()
    {
        // execute the query
        $this->rows = $this->sqlService->getRows();
        return $this->rows;
    }
}
