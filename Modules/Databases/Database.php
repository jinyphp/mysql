<?php
/*
 * jiny Modules.
 * (c) hojinlee <infohojin@gmail.com>
 */
namespace Modules\Databases;

use PDO;

class Database
{
    // 데이터베이스 접속정보
    private $host;
    private $dbuser;
    private $dbpassword;
    private $charset;
    private $schema;

    public function __construct($dbinfo=null)
    {
        // 데이터정보가 있는 경우 처리함
        if ( $dbinfo && is_array($dbinfo)) {
            foreach ($dbinfo as $key => $value) {
                // echo "$key => $value \n";
                // setter 메소드명 조합
                $action = "set".ucfirst($key); // key값의 첫글자만 대문자로 설정함
                // echo $action."\n";
                $this->$action($value); // 메서드 호출 및 기본값 설정
            }
        }
    }

    /**
     * 데이터베이스 접속처리 루틴
     */
    public $conn;
    public function connect()
    {
        if (extension_loaded("PDO") && extension_loaded("pdo_mysql")) {
            
            $host  = "mysql:";
            $host .= "dbname=".$this->schema;
            $host .= ";charset=".$this->charset;
            $host .= ";host=".$this->host;

            try {
                $this->conn = new \PDO($host, $this->dbuser, $this->dbpassword);
                echo "데이터 베이스 접속 성공!\n";

                // PDO 오류 숨김모드 해제, 
                // 오류 발생시 Exception을 발생시킨다.
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // PDO connection을 반환합니다.
                return $this->conn;

            } catch (PDOException $e) {
                echo "접속 실패\n";
                echo $e->getMessage();
            }
        
        } else {
            echo "PDO 드라이버가 활성화 되어 있지 않습니다.\n";
            exit(1); // 오류 종료
        }
    }

    /**
     * 패스워드 설정
     */
    public function setPassword($password)
    {
        $this->dbpassword = $password;
        return $this; // 메서드체인
    }

    /**
     * 사용자 설정
     */
    public function setUser($user)
    {
        $this->dbuser = $user;
        return $this; // 메서드체인
    }

    /**
     * 스키마설정 설정
     */
    public function setSchema($schema)
    {
        $this->schema = $schema;
        return $this; // 메서드체인
    }

    /**
     * 문자셋 설정
     */
    public function setCharset($charset="utf8")
    {
        $this->charset = $charset;
        return $this; // 메서드체인
    }

    /**
     * 호스트 설정
     */
    public function setHost($host="localhost")
    {
        $this->host = $host;
        return $this; // 메서드체인
    }


    /**
     * rawSQL 쿼리 실행
     */
    private $stmt;
    public function query($query)
    {
        if (!$this->conn) $this->connect(); // db접속 상태를 확인
        $this->stmt = null; // 초기화
        $this->stmt = $this->conn->query($query); // 쿼리준비

        return $this;
    }

    public function statement()
    {
        return $this->stmt; // PDOStatement 상태 객체를 반환
    }

    /**
     * stmt Fetch 처리 
     */

    public function fetchObj($stmt=null)
    {
        if(!$stmt) $stmt = $this->stmt; //주어진 stmt가 없으면, 이전 쿼리의 stmt를 설정함.
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function fetchObjAll($stmt=null)
    {
        $rows = []; // 배열 초기화
        while ($row = $this->fetchObj($stmt)) {
            $rows []= $row;
        }
        return $rows;
    }

    public function fetchAssoc($stmt=null)
    {
        if(!$stmt) $stmt = $this->stmt; //주어진 stmt가 없으면, 이전 쿼리의 stmt를 설정함.
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchAssocAll($stmt=null)
    {
        $rows = []; // 배열 초기화
        while ($row = $this->fetchAssoc($stmt)) {
            $rows []= $row;
        }
        return $rows;
    }

    /**
     * 테이블 생성
     */
    public function tableCreateEmpty($name)
    {
        if ($name) {
            // 테이블 생성쿼리
            $query = "CREATE TABLE `".$this->schema."`.`".$name."` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `created_at` datetime,
                `updated_at` datetime,
                primary key(`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $this->query($query); 
        } else {
            echo "생성할 테이블명이 없습니다.\n";
            exit(1);
        }
    }

    public function tableList() : array
    {
        $query = "SHOW TABLES"; // 테이블 목록쿼리
        $this->query($query);
        
        $rows = []; // 배열 초기화
        while ($row = $this->fetchAssoc($this->stmt)) {
            $rows []= $row["Tables_in_".$this->schema]; // 테이블명만 추출
        }
        return $rows;
    }

    public function isTable($name) : bool
    {
        $tables = $this->tableList(); // 테이블 목록
        return in_array($name, $tables);
    }

    public function tableCreate($name, $columns)
    {
        if ($name) {
            // 테이블 생성쿼리
            $query = "CREATE TABLE `".$this->schema."`.`".$name."` (
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

            $this->query($query); 
        } else {
            echo "생성할 테이블명이 없습니다.\n";
            exit(1);
        }
    }

    /**
     * 테이블 정보
     */
    public function tableDesc($name)
    {
        $query = "DESC ".$name.";"; // 테이블 구조
        return $this->query($query)->fetchAssocAll();
    }

    /*
     * 컬럼을 추가합니다.
     */
    public function tableAddColums($name, $columns)
    {
        $query = "";
        foreach ($columns as $key => $value) {
            // Alter 수정쿼리를 생성합니다.
            $query .= "ALTER table ".$name." add ".$key." ".$value.";";
        }

        $this->query($query);

        return $this;
    }

    /*
     * 컬럼을 수정합니다.
     */
    public function tableModifyColums($name, $columns)
    {
        $query = "";
        foreach ($columns as $key => $value) {
            // Alter 수정쿼리를 생성합니다.
            $query .= "ALTER table ".$name." modify ".$key." ".$value.";";
        }

        $this->query($query);

        return $this;
    }

    /**
     * 컬럼 삭제
     */
     public function tableDropColums($name, $columns)
     {
        $query = "";
        foreach ($columns as $value) {
            // Alter 수정쿼리를 생성합니다.
            $query .= "ALTER table ".$name." drop ". $value.";";
        }

        $this->query($query);

        return $this;
     }
    
    /**
     * 컬럼이동
     */
    public function tableChangeColums($name, $columnss)
    {
        $query = "";
        foreach ($columnss as $old => $column) {
            foreach ($column as $key => $value) {
                // Alter 수정쿼리를 생성합니다.
                $query .= "ALTER table ".$name." change $old $key ". $value.";";
            }            
        }

        $this->query($query);

        return $this;
    }

    /**
     * 스키마목록
     */
    public function schemaList()
    {
        $query = "show schemas;";
        $this->query($query);
        
        $rows = []; // 배열 초기화
        while ($row = $this->fetchAssoc($this->stmt)) {
            $rows []= $row["Database"]; // 테이블명만 추출
        }
        return $rows;
    }

    public function schemaCreate($name)
    {
        $query = "create database ".$name.";";
        $this->query($query);

        return $this;
    }

    public function insertBind($query, $bind)
    {
        if (!$this->conn) $this->connect(); // db접속 상태를 확인
        $this->stmt = $this->conn->prepare($query);

        foreach ($bind as $field => &$value) {
            $this->stmt->bindParam(':'.$field, $value);
        }

        $this->stmt->execute();
        return $this->conn->lastInsertId();
    }

    // 마지막 입력한 데이터의 id값 반환
    public function lastInsertId(){
        return $this->conn->lastInsertId();
    }

    public function insert($table, array $data)
    {
        // 쿼리작성(bind)
        $query = "INSERT `".$this->schema."`.`".$table."` SET ";
        foreach($data as $key => $value) {
            $query .= $key." = :".$key.",";
        }
        $query = rtrim($query,','); // 마지막 콤마 제거
        $query .= ";";

        return $this->insertBind($query, $data);
    }

    /**
     * 데이터목록
     */
    public function select($table, $fields=null)
    {
        //쿼리 생성
        $query = "SELECT ";
        if(is_array($fields)) {
            // 선택필드
            foreach ($fields as $value) {
                $query .= $value.",";
            }
            $query = rtrim($query,','); // 마지막 콤마 제거
        } else {
            // 전체목록
            $query .= "*";
        }        
        $query .= " FROM `".$this->schema."`.`".$table."`;";

        return $this->query($query);
    }

    /**
     * 갱신
     */
    public function updateBind($query, $bind)
    {
        if (!$this->conn) $this->connect(); // db접속 상태를 확인
        $this->stmt = $this->conn->prepare($query);

        foreach ($bind as $field => &$value) {
            $this->stmt->bindParam(':'.$field, $value);
        }

        $this->stmt->execute();
        return $this;
    }

    /**
     * 삭제
     */
    public function deleteBind($query, $bind)
    {
        if (!$this->conn) $this->connect(); // db접속 상태를 확인
        $this->stmt = $this->conn->prepare($query);

        foreach ($bind as $field => &$value) {
            $this->stmt->bindParam(':'.$field, $value);
        }

        $this->stmt->execute();
        return $this;
    }
}