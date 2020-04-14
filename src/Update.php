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

class Update extends Database
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
        //쿼리 생성
        $query = "UPDATE ".$this->_tablename." SET ";

        // 갱신 데이터
        if(is_array($fields)) {
            foreach ($fields as $key => $value) {
                if($key == "id") continue;
                if($key == "created_at") continue;
                if($key == "updated_at") {
                    $query .= "`$key`= '".date("Y-m-d H:i:s")."',";
                    continue;
                }
                $query .= "`$key`= '".$value."',";
            }
        }

        $query = rtrim($query,','); // 마지막 콤마 제거
        return $query;
    }


    public function id($id)
    {
        $query = $this->queryBuild($this->_fields); // 쿼리 생성
        $query .= " WHERE id=:id"; // 조건값

        $stmt = $this->_db->conn->prepare($query);

        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function bind($query, $bind)
    {
        $stmt = $this->_db->conn->prepare($query);

        foreach ($bind as $field => &$value) {
            $stmt->bindParam(':'.$field, $value);
        }

        $stmt->execute();
        return $this;
    }
}