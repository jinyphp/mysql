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

class Insert extends Database
{
    public function __construct($tablename, $db)
    {
        $this->_tablename = $tablename;
        $this->_db = $db;
        $this->_schema = $db->getSchema();

        // db접속 상태를 확인
        if (!$this->_db->conn) $this->_db->connect(); 
    }



    private $_fields = [];
    public function setFields($fields)
    {
        $this->_fields = $fields;
        return $this;
    }

    private function queryBuild($fields)
    {
        // 쿼리작성(bind)
        $query = "INSERT `".$this->_schema."`.`".$this->_tablename."` SET ";
        foreach($fields as $key => $value) {
            $query .= $key." = :".$key.",";
        }
        $query = rtrim($query,','); // 마지막 콤마 제거
        $query .= ";";
        return $query;
    }

    
    public function bind($query, $bind)
    {
        $stmt = $this->_db->conn->prepare($query);

        foreach ($bind as $field => &$value) {
            $stmt->bindParam(':'.$field, $value);
        }

        return $stmt;
    }

    // 마지막 입력한 데이터의 id값 반환
    public function last(){
        return $this->_db->conn->lastInsertId();
    }

    public function save($data=null)
    {
        if(!$data) $data = $this->_fields;

        $query = $this->queryBuild($data); // 쿼리 생성
        $stmt = $this->bind($query, $data);
        $stmt->execute();

        return $this->last();
    }

}