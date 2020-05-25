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

class Schema extends Database
{
    public function __construct($db)
    {
        $this->_db = $db;
        $this->_schema = $db->getSchema();

        // db접속 상태를 확인
        if (!$this->_db->conn()) $this->_db->connect(); 
    }

    public function create($name)
    {
        $query = "create database ".$name.";";
        $this->_db->query($query);

        return $this;
    }

    // 스키마 존재여부 확인
    public function is($schema)
    {
        $query = "show schemas;";
        $this->_db->query($query);
        
        while ($row = $this->_db->fetchAssoc()) {
            if($row["Database"] == $schema) return true;
        }

        return false;
    }

    /**
     * 스키마목록
     */
    public function list($type=TRUE)
    {
        $query = "show schemas;";
        $this->_db->query($query);
        
        $rows = []; // 배열 초기화
        while ($row = $this->_db->fetchAssoc()) {
            if($type) {
                $rows []= $row["Database"]; // 테이블명만 추출
            } else {
                $rows []= $row; // 일반
            }
        }
        return $rows;
    }

    /**
     * 스키마의 테이블 갯수를 반환합니다.
     */
    public function totalTables($schema)
    {
        $query = "SELECT count(*) AS TOTALTABLES FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '".$schema."';";
        $num = $this->_db->query($query)->fetchAssoc();
        return $num['TOTALTABLES'];
    }


}