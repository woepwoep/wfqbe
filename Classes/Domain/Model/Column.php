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
 * Column
 */
class Column
{
    /** string $name */
    protected $name;

    /** mixed $value */
    protected $value;


    /** @var \RedSeadog\Wfqbe\Domain\Model\Query $query */
    public function __construct($name,$value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

}
