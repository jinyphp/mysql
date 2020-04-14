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

use Jiny\Mysql\Database;

// 데이터 조회
class Select extends Database
{
    public function __construct($tablename, $db)
    {
        $this->_tablename = $tablename;
        $this->_db = $db;
        $this->_schema = $db->getSchema();

        // db접속 상태를 확인
        if (!$this->_db->conn) $this->_db->connect(); 
    }


    public function all()
    {
        //쿼리 생성
        $query = "SELECT * ";
        $query .= " FROM `".$this->_schema."`.`".$this->_tablename."`;";
        $this->_db->query($query);
        return $this->_db->fetchObjAll();
    }

    private $_fields = [];
    public function setFields($fields)
    {
        $this->_fields = $fields;
        return $this;
    }

    private function queryBuild($fields)
    {
        //쿼리 생성
        $query = "SELECT ";

        if(is_array($fields) && count($fields)) {
            // 선택필드
            foreach ($fields as $value) {
                $query .= $value.",";
            }
            $query = rtrim($query,','); // 마지막 콤마 제거
       
        } else {
            $query .= "*"; // 전체목록
        }    

        $query .= " FROM `".$this->_schema."`.`".$this->_tablename."`;";

        return $query;
    }



    public function fetchObjAll($stmt=null)
    {
        $query = $this->queryBuild($this->_fields); // 쿼리 생성
        return $this->_db->query($query)->fetchObjAll();
    }

    public function fetchObj($stmt=null)
    {
        $query = $this->queryBuild($this->_fields); // 쿼리 생성
        return $this->_db->query($query)->fetchObj();
    }

    public function fetchAssocAll($stmt=null)
    {
        $query = $this->queryBuild($this->_fields); // 쿼리 생성
        return $this->_db->query($query)->fetchAssocAll();
    }

    public function fetchAssoc($stmt=null)
    {
        $query = $this->queryBuild($this->_fields); // 쿼리 생성
        return $this->_db->query($query)->fetchAssoc();
    }



}