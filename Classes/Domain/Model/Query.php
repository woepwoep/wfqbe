<?php
namespace RedSeadog\Wfqbe\Domain\Model;

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
 * Query
 */
class Query extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /** @var string */
    protected $title = '';

    /** @var string */
    protected $description = '';

    /** @var string */
    protected $query = '';

    /** @var string */
    protected $connection = '';

    /** @var string */
    protected $search = '';

    /** @var string */
    protected $insertq = '';

    /** @var string */
    protected $type = '';

    /** @var string */
    protected $searchinquery = '';


    /** @return string $title */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /** @return string $description */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /** @return string $query */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param string $query
     * @return void
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /** @return string $connectionname */
    public function getConnectionname()
    {
        return $this->connectionname;
    }

    /**
     * @param string $connectionname
     * @return void
     */
    public function setConnectionname($connectionname)
    {
        $this->connectionname = $connectionname;
    }

    /** @return string $search */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @param string $search
     * @return void
     */
    public function setSearch($search)
    {
        $this->search = $search;
    }

    /** @return string $insertq */
    public function getInsertq()
    {
        return $this->insertq;
    }

    /**
     * @param string $insertq
     * @return void
     */
    public function setInsertq($insertq)
    {
        $this->insertq = $insertq;
    }

    /** @return string $type */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return void
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /** @return string $searchinquery */
    public function getSearchinquery()
    {
        return $this->searchinquery;
    }

    /**
     * @param string $searchinquery
     * @return void
     */
    public function setSearchinquery($searchinquery)
    {
        $this->searchinquery = $searchinquery;
    }
}
