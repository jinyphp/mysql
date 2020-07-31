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
        $this->_db->setQuery($query);
        return $this;
    }

    // 쿼리만 초기화
    public function clearQuery()
    {
        $this->_db->setQuery(null);
        return $this;
    }

    public function getQuery()
    {
        return $this->_db->getQuery();
    }

    private $_auto = 0x00;
    const AUTO_TABLE = 0x01;
    const AUTO_FILED = 0x02;
    public function autoCreate() { return $this->autoTable(); } // alias
    public function createAuto() { return $this->autoTable(); } // alias
    public function tableAuto() { return $this->autoTable(); } // alias
    public function autoTable()
    {
        $this->_auto |=0x01;
        return $this;
    }
    public function fieldAuto() { return $this->autoField(); } // alias
    public function autoField()
    {
        $this->_auto |=0x02;
        return $this;
    }
    public function autoset()
    {
        return $this->_auto;
    }

    /**
     * 설정된 쿼리를 실행합니다.
     */
    public function run($data=null)
    {
        //echo "쿼리run";
        static $count=0;
        // 직접 데이터 전달시, 필드항목을 추가합니다.
        if ($data) $this->setFields($data);

        // 쿼리를 실행합니다.
        $result = $this->_db->run($data);
        //echo "<br>";
        //print_r($result);
        //exit;
        
        // 오류발생 처리
        if ($result instanceof \PDOException) {
            // lazy loading...
            $Err = new \Jiny\Mysql\Error($this->_db);
            $Err->error($result, $this);
            
            // 재귀실행 카운트
            if($count<3) {
                $count++;
                return $this->run($data); 
            } else {
                echo "쿼리오류, 재귀실행 초과!!!";
            }
            
            exit;
        }
        
        return $result;
    }

}