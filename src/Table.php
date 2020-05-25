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

// 테이블 관련작업 클래스
class Table extends Database
{
    public function __construct($tablename, $db)
    {
        $this->_tablename = $tablename;
        $this->_db = $db;
        $this->_schema = $db->getSchema();

        // db접속 상태를 확인
        if (!$this->_db->conn()) $this->_db->connect(); 
    }

    public function count($where=null)
    {
        $query = "SELECT count(id) from ".$this->_tablename;

        $this->_db->query($query);
        $this->_db->statement()->execute();
        $num = $this->_db->fetchAssoc();

        return $num['count(id)'];
    }


    /**
     * 테이블 생성
     */
    public function createEmpty($name=null)
    {
        // 매개변수명으로 테이블을 생성합니다.
        if(!$name) $name = $this->_tablename;

        // 테이블 생성쿼리
        $query = "CREATE TABLE `".$this->_schema."`.`".$name."` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `created_at` datetime,
            `updated_at` datetime,
            primary key(`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $this->_db->query($query);
        $this->_tablename = $name; // 생성후 테이블명을 재설정 합니다.
    }

    /**
     * 테이블 목록
     */
    public function list($type=TRUE) : array
    {
        $query = "SHOW TABLES"; // 테이블 목록쿼리
        $this->_db->query($query);
        
        $rows = []; // 배열 초기화
        while ($row = $this->_db->fetchAssoc()) {
            if ($type) {
                $rows []= $row; 
            } else {
                $rows []= $row["Tables_in_".$this->_schema]; // 테이블명만 추출
            }            
        }
        return $rows;
    }

    public function is($name=null) : bool
    {
        if(!$name) $name = $this->_tablename;

        $tables = $this->list(false); // 테이블 목록
        return in_array($name, $tables);
    }

    /**
     * 연상배열 정보를 통하여 테이블을 생성합니다.
     */
    public function create($columns)
    {
        $name = $this->_tablename;
        
        // 테이블 생성쿼리
        $query = "CREATE TABLE `".$this->_schema."`.`".$name."` (
            `id` int(11) NOT NULL AUTO_INCREMENT,";

        // 사용자 컬럼
        foreach ($columns as $key => $value) {
            $query .= "`".$key."` ".$value.",";
        }

        // 기본골격
        $query .= "`created_at` datetime,";
        $query .= "`updated_at` datetime,";
        $query .= "primary key(`id`) ) ";
        $query .= "ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $this->_db->query($query); 
       
    }

    /**
     * 테이블 정보
     */
    public function desc($name=null)
    {
        if(!$name) $name = $this->_tablename;

        $query = "DESC ".$name.";"; // 테이블 구조
        return $this->_db->query($query)->fetchAssocAll();
    }

    public function rename($old, $new)
    {
        $query = "ALTER TABLE `".$this->_schema."`.`".$old."` RENAME TO  `".$this->_schema."`.`".$new."`" ;
        $this->_db->query($query); 
    }

    // 테이블 정보출력
    public function info($name=null)
    {
        if(!$name) $name = $this->_tablename;

        $query = "select * from INFORMATION_SCHEMA.tables where TABLE_SCHEMA='".$this->_schema."' and TABLE_NAME='".$name."'";
        return $this->_db->query($query)->fetchAssocAll();
    }

    /*
     * 컬럼을 추가합니다.
     */
    public function addColums($columns)
    {
        $name = $this->_tablename;

        $query = "";
        foreach ($columns as $key => $value) {
            // Alter 수정쿼리를 생성합니다.
            $query .= "ALTER table ".$name." add ".$key." ".$value.";";
        }

        $this->_db->query($query);

        return $this;
    }

    /*
     * 컬럼을 수정합니다.
     */
    public function modifyColums($columns)
    {
        $name = $this->_tablename;

        $query = "";
        foreach ($columns as $key => $value) {
            // Alter 수정쿼리를 생성합니다.
            $query .= "ALTER table ".$name." modify ".$key." ".$value.";";
        }

        $this->_db->query($query);

        return $this;
    }

    /**
     * 컬럼 삭제
     */
     public function dropColums($columns)
     {
        $name = $this->_tablename;

        $query = "";
        foreach ($columns as $value) {
            // Alter 수정쿼리를 생성합니다.
            $query .= "ALTER table ".$name." drop ". $value.";";
        }

        $this->_db->query($query);
        return $this;
     }
    
    /**
     * 컬럼이동
     */
    public function changeColums($columnss)
    {
        $name = $this->_tablename;
        
        $query = "";
        foreach ($columnss as $old => $column) {
            foreach ($column as $key => $value) {
                // Alter 수정쿼리를 생성합니다.
                $query .= "ALTER table ".$name." change $old $key ". $value.";";
            }            
        }

        $this->_db->query($query);

        return $this;
    }

    /**
     * 
     */
}