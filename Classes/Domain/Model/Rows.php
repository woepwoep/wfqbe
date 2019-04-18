<?php
namespace RedSeadog\Wfqbe\Domain\Model;

use \RedSeadog\Wfqbe\Domain\Repository\QueryRepository;

/***
 *
 * This file is part of the "wfqbe" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019 Ronald Wopereis <woepwoep@gmail.com>, Red-Seadog
 *
 ***/

/**
 * Rows
 */
class Rows extends \RedSeadog\Wfqbe\Domain\Model\Query
{
    /** @var \RedSeadog\Wfqbe\Domain\Repository\QueryRepository */
    protected $query;

    /** @var array */
    protected $rows;


    /** @var \RedSeadog\Wfqbe\Domain\Model\Query $query */
    public function __construct($query)
    {
        $this->query = $query;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function setQuery(\RedSeadog\Wfqbe\Domain\Model\Query $query)
    {
        $this->query = $query;
    }

    public function getRows()
    {
        return $this->rows;
    }

    protected function executeQuery()
    {
    }
}
