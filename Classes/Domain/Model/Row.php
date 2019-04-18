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
 * Row
 */
class Row
{
    /** @var array */
    protected $row;


    public function getRow()
    {
        return $this->row;
    }

    public function setRow(\RedSeadog\Wfqbe\Domain\Model\Row $row)
    {
        $this->row = $row;
    }
}
