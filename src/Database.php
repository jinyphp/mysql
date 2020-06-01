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

    /**
     * 테이블명을 설정합니다.
     */
    public function setTablename($tablename)
    {
        $this->_tablename = $tablename;
        return $this;
    }

    /**
     * 설정된 테이블명을 읽어옵니다.
     */
    public function getTablename()
    {
        return $this->_tablename;
    }

    /**
     * 스키마를 설정합니다.
     */
    public function setSchema($schema)
    {
        $this->_schema = $schema;
        return $this;
    }

    /**
     * 설정된 스키마를 읽어옵니다.
     */
    public function getSchema()
    {
        return $this->_schema;
    }

    /**
     * bind된 쿼리를 실행합니다.
     */
    protected $_stmt;
    public function execute($stmt=null)
    {
        if(!$stmt) $stmt = $this->_stmt;
        try {
            $stmt->execute();
            return $stmt;
        } catch(\PDOException $e) {
            return $e;            
        }
    }

    public function statement()
    {
        return $this->_stmt;
    }

    /**
     * 빌더쿼리 관리
     */
    protected $_query=null;
    // 쿼리빌더에 직접 쿼리를 설정합니다.
    public function setQuery($query)
    {
        $this->_query = $query;
        return $this;
    }

    // 쿼리만 초기화
    public function clearQuery()
    {
        $this->_query = null;
        return $this;
    }

    public function getQuery()
    {
        return $this->_query;
    }

}