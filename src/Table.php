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
    const PRIMARYKEY = 'id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function __construct($tablename, $db)
    {
        $this->_tablename = $tablename;
        $this->_db = $db;
        $this->_schema = $db->getSchema();

        // db접속 상태를 확인
        if (!$this->_db->conn()) $this->_db->connect(); 
    }

    // 객체 설정값을 초기화 합니다.
    public function clear()
    {
        $this->_engine = "InnoDB";
        $this->_charset = "utf8";
        $this->_fields = [];
    }

    /**
     * 엔진 설정
     */
    private $_engine = "InnoDB";
    public function engine($engine)
    {
        $this->_engine = $engine;
        return $this;
    }

    /**
     * 문자셋 설정
     */
    private $_charset="utf8";
    public function charset($charset)
    {
        $this->_charset = $charset;
        return $this;
    }

    /**
     * 테이블의 row 갯수를 확인합니다.
     */
    public function count($where=null)
    {
        $query = "SELECT count(id) from ".$this->_tablename;

        $this->_db->query($query);
        $this->_db->statement()->execute();
        $num = $this->_db->fetchAssoc();

        return $num['count(id)'];
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

    private $_fields = [];
    /**
     * 필드 설정
     */
    public function setField($name, $value)
    {
        $this->_fields[$name] = $value;
        return $this;
    }

    /**
     * 빌더 필드 삭제
     */
    public function unsetField($name)
    {
        unset($this->_feilds[$name]);
        return $this;
    }

    public function setFields($fields)
    {
        foreach ($fields as $f => $v) {
            $this->_fields[$f] = $v;
        }
        return $this;
    }

    // 전체 초기화
    public function clearFields()
    {
        $this->_fields = [];
        return $this;
    }




    /**
     * 연상배열 정보를 통하여 테이블을 생성합니다.
     */
    public function create($columns=null)
    {
        $name = $this->_tablename;
        if ( $this->is($name=null)) return "중복된 테이블 입니다.";

        if(\is_array($columns)) { // array타입
            
            // 컬럼 매개변수가 없는 경우, 컬럼 필드를 읽어 옵니다.
            // if (!$columns) $columns = $this->_fields; 

            // 기본 필드 중복 생성 방지
            unset($columns[self::PRIMARYKEY]);
            unset($columns[self::CREATED_AT]);
            unset($columns[self::UPDATED_AT]);

            // sql쿼리생성
            $query = $this->createQuery($columns)->getQuery();
            $this->_db->query($query);
            return $this;

        } else {
            // 컬럼정보가 없는 경우, 빈테이블 생성
            return $this->createEmpty($this->_tablename);
        }
    }

    /**
     * 테이블 생성
     */
    public function empty($name=null) { return $this->createEmpty($name); }
    public function createEmpty($name=null)
    {
        // 매개변수명으로 테이블을 생성합니다.
        if($name) $this->_tablename = $name;

        // 테이블 생성쿼리
        $query = "CREATE TABLE `".$this->_schema."`.`".$this->_tablename."` (
            `".self::PRIMARYKEY."` int(11) NOT NULL AUTO_INCREMENT,
            `".self::CREATED_AT."` datetime,
            `".self::UPDATED_AT."` datetime,
            primary key(`id`)
        ) ENGINE=".$this->_engine." DEFAULT CHARSET=".$this->_charset.";";

        $this->_db->query($query);
        return $this;
    }

    /**
     * 컬럼 배열 정보를 이용하여 쿼리를 생성합니다.
     */
    private function createQuery($columns)
    {
        // 테이블 생성쿼리
        $query = "CREATE TABLE `".$this->_schema."`.`".$this->_tablename."` (
            `".self::PRIMARYKEY."` int(11) NOT NULL AUTO_INCREMENT,";

        // 사용자 컬럼
        foreach ($columns as $key => $value) {
            $query .= "`".$key."` ".$value.",";
        }

        // 기본골격
        $query .= "`".self::CREATED_AT."` datetime,";
        $query .= "`".self::UPDATED_AT."` datetime,";
        $query .= "primary key(`".self::PRIMARYKEY."`) ) ";
        $query .= "ENGINE=".$this->_engine." DEFAULT CHARSET=".$this->_charset.";";

        // 생성한 쿼리를 내부설정 : database
        $this->setQuery($query);
        return $this;
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

    /**
     * 테이블 삭제
     */
    public function isDrop($table=null)
    {
        if ( $this->is($name=null)) { // 테이블 존재 여부를 확인합니다.
            return $this->drop($table);
        }
        return $this;
    }

    public function drop($table=null)
    {
        if($table) {
            // 입력된 테이블 삭제
            if (is_array($table)) { // 다중 테이블
                $query = "DROP TABLES IF EXISTS ";
                foreach ($table as $name) {
                    $query .= $name.",";
                }
                $query = rtrim($query,',');
            } else { // 단일 테이블                
                $query = "DROP TABLES IF EXISTS ".$table;
            }

        } else {
            // 현재의 테이블 삭제
            $query = "DROP TABLES IF EXISTS ".$this->_tablename;;
        }
        
        $this->_db->query($query);
        return $this;
    }

    // --- 테이블 컬럼 변경 ---
    // https://mysql.jiny.dev/builder/table/colums

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



    //////////
    /**
     * 테이블 코멘트 설정
     */
    public function comment($comment)
    {
        // db.table
        $query = "ALTER TABLE `".$this->_schema."`.`".$this->_tablename."` COMMENT = '".$comment."' ;";
        $this->_db->query($query);
        return $this;
    }

    /**
     * 테이블 코멘트 읽기
     */
    public function getComment()
    {
        $query = "SELECT TABLE_NAME, TABLE_COMMENT FROM information_schema.tables ";
        $query .= "where table_schema='$this->_schema' ";
        if($this->_tablename) {
            $query .= "and table_name='$this->_tablename' ;";
            $this->_db->query($query);
            return $this->_db->fetchObj();
        } else {
            $this->_db->query($query);
            return $this->_db->fetchObjAll();
        } 
    }



    public function fieldComment($database, $tableName, $field, $message)
    {
        $info = $this->descField($tableName, $field);
        $query = "ALTER TABLE `$database`.`$tableName` ";
        $query .= "CHANGE COLUMN `$field` `$field` ".$info['Type']." ";
        if($info['Null']) $query .= "NULL "; else $query .= "NOT NULL ";
        if($info['Default']) $query .= "DEFAULT ".$info['Default']." "; else $query .= "DEFAULT NULL ";
        $query .= "COMMENT '$message';";

        if (!$this->conn) $this->connect();
        $stmt = $this->conn->prepare($query);

        $stmt->execute();
    }

    public function getFieldComment($database, $tableName)
    {
        $query = "SELECT TABLE_NAME, COLUMN_NAME, COLUMN_COMMENT FROM information_schema.COLUMNS ";
        $query .= "where table_schema='$database' ";
        if($tableName) $query .= "and table_name='$tableName' ;";

        if (!$this->conn) $this->connect();
        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 
     */
}