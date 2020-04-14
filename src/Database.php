<?php
/*
 * This file is part of the jinyPHP package.
 *
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Jiny\Mysql;

abstract class Database
{
    protected $_tablename;
    protected $_schema;
    protected $_db;

    public function setTablename($tablename)
    {
        $this->_tablename = $tablename;
        return $this;
    }

    public function getTablename()
    {
        return $this->_tablename;
    }

    public function setSchema($schema)
    {
        $this->_schema = $schema;
        return $this;
    }

    public function getSchema()
    {
        return $this->_schema;
    }
}