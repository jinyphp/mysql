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

use PDO;

class Connection
{

    // 싱글턴 초기화
    use \Jiny\Petterns\Singleton;
    private function init()
    {

    }

    // 데이터베이스 접속정보
    private $host = "localhost";
    private $dbuser;
    private $dbpassword;
    private $charset="utf8";
    private $schema;

    public function __construct($dbinfo=null)
    {
        // 데이터정보가 있는 경우 처리함
        // echo "생성자 동작";
        //print_r($dbinfo);
        //exit;

        if ( $dbinfo && is_array($dbinfo)) {
            foreach ($dbinfo as $key => $value) {
                // echo "$key => $value \n";
                // setter 메소드명 조합
                $action = "set".ucfirst($key); // key값의 첫글자만 대문자로 설정함
                // echo $action."\n";
                $this->$action($value); // 메서드 호출 및 기본값 설정
            }
        }

        $this->connect(); // DB접속
    }

    /**
     * 데이터베이스 접속처리 루틴
     */
    private $_conn;
    public function connect()
    {
        if (extension_loaded("PDO") && extension_loaded("pdo_mysql")) {
            
            $host  = "mysql:";
            $host .= "dbname=".$this->schema;
            $host .= ";charset=".$this->charset;
            $host .= ";host=".$this->host;

            try {
                $this->_conn = new \PDO($host, $this->dbuser, $this->dbpassword);
                // echo "데이터 베이스 접속 성공!\n";

                // PDO 오류 숨김모드 해제, 
                // 오류 발생시 Exception을 발생시킨다.
                $this->_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // PDO connection을 반환합니다.
                return $this->_conn;

            } catch (PDOException $e) {
                echo "접속 실패\n";
                echo $e->getMessage();
            }
        
        } else {
            echo "PDO 드라이버가 활성화 되어 있지 않습니다.\n";
            exit(1); // 오류 종료
        }
    }

    public function conn()
    {
        return $this->_conn;
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

    public function getSchema()
    {
        return $this->schema;
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
    private $_query;
    public function query($query)
    {
        // echo $query;
        $this->stmt = $this->_conn->query($query); // 쿼리준비

        return $this;
    }

    /**
     * raw쿼리를 설정합니다.
     */
    public function setQuery($query)
    {
        $this->_query = $query;
        return $this;
    }
    public function getQuery()
    {
        return $this->_query;
    }

    public function clear()
    {
        $this->_query = null;
        $this->stmt = null;
        return $this;
    }

    public function binds($query, $bind)
    {
        if (!$this->_conn) $this->connect(); // db접속 상태를 확인

        // 쿼리문을 준비합니다.
        $this->stmt = $this->_conn->prepare($query);
        // 값을 바인딩합니다.
        foreach ($bind as $field => &$value) {
            $this->stmt->bindParam(':'.$field, $value);
        }

        return $this->stmt;
    }

    public function setBinds($data)
    {
        if (!$this->_conn) $this->connect(); // db접속 상태를 확인        
        $this->stmt = $this->_conn->prepare($this->_query); // 쿼리문을 준비합니다.
        foreach ($data as $field => &$value) { // 값을 바인딩합니다.
            $this->stmt->bindParam(':'.$field, $value);
        }

        return $this;
    }

    public function setBind($key, $value)
    {
        //if (!$this->_conn) $this->connect(); // db접속 상태를 확인  
        if (!$this->stmt) {
            $this->stmt = $this->_conn->prepare($this->_query); // 쿼리문을 준비합니다.
        }
        $this->stmt->bindParam(':'.$key, $value);
        return $this;
    }

    /**
     * 설정된 쿼리를 실행합니다.
     */
    public function run($data=[])
    {
        //if (!$this->stmt) {
            // 쿼리문을 준비합니다.
            $this->stmt = $this->_conn->prepare($this->_query);
            if (is_array($data)) {
                foreach ($data as $field => &$value) { // 값을 바인딩합니다.
                    $this->stmt->bindParam(':'.$field, $value);
                }
            }            
        //}

        //echo "실행";
        try {
            $this->stmt->execute();
            return $this;

        } catch(\PDOException $e) {
            return $e;
        }
        
    }

    /**
     * bind statement를 실행합니다.
     */
    public function execute()
    {
        $this->stmt->execute();
    }

    public function statement()
    {
        return $this->stmt; // PDOStatement 상태 객체를 반환
    }

    public function setStatement($stmt)
    {
        $this->stmt = $stmt;
    }



    /**
     * stmt Fetch 처리 
     */
    // PDO::FETCH_NUM : 숫자 인덱스 배열 반환
    // PDO::FETCH_ASSOC : 컬럼명이 키인 연관배열 반환
    // PDO::FETCH_BOTH : 위 두가지 모두
    // PDO::FETCH_OBJ : 컬럼명이 프로퍼티인 인명 객체 반환
    public function fetch($type=PDO::FETCH_OBJ)
    {
        return $this->stmt->fetch($type);
    }

    public function fetchAll($type=PDO::FETCH_ASSOC)
    {
        return $this->stmt->fetchAll($type);
    }

    public function fetchObj($stmt=null)
    {
        if($stmt) $this->stmt = $stmt; // stmt 교체
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    public function fetchObjAll($stmt=null)
    {
        if($stmt) $this->stmt = $stmt; // stmt 교체
        $rows = []; // 배열 초기화
        while ($row = $this->fetchObj()) {
            $rows []= $row;
        }
        return $rows;
    }

    public function fetchAssoc($stmt=null)
    {
        if($stmt) $this->stmt = $stmt; // stmt 교체
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchAssocAll($stmt=null)
    {
        if($stmt) $this->stmt = $stmt; // stmt 교체
        $rows = []; // 배열 초기화
        while ($row = $this->fetchAssoc()) {
            // echo "데이터 읽기";
            // print_r($row);
            $rows []= $row;
        }
        return $rows;
    }






    /**
     * 스키마 확장
     */
    private $_schema;
    public function schema()
    {
        // 플라이웨이트 공유객체 관리
        if (!isset($this->_schema)) {
            $this->_schema = new \Jiny\Mysql\Schema($this); // 객체를 생성합니다.
        } else {
            
        }

        $this->_query = null;
        $this->stmt = null;

        // 객체반환
        return $this->_schema;
    }

    /**
     * 테이블 확장
     */
    private $_table;
    public function table($tablename=null)
    {
        if (!isset($this->_table)) {
            // 공유객체 생성
            $this->_table = new \Jiny\Mysql\Table($tablename, $this); 
        } else {
            // 공유객체 반환
            if ($tablename) {
                // 테이블명만 재설정합니다.
                $this->_table->setTablename($tablename); 
            } 
        }

        $this->clear(); // 초기화
        return $this->_table;
    }



    /**
     * 데이터 삽입 확장
     */
    private $_insert;
    public function insert($tablename, $fields=null)
    {
        // 플라이웨이트 공유객체 관리
        if (!isset($this->_insert)) {
            // 객체를 생성합니다.
            $this->_insert = new \Jiny\Mysql\Insert($tablename, $this); 
        } 

        // raw쿼리 확인
        $q = \explode(" ",$tablename);
        if(isset($q[0]) && strtoupper($q[0]) == "INSERT") {
            // raw 쿼리 입력
            if(count($q)>3) {
                // raw 쿼리 설정
                // echo $tablename;
                $this->setQuery($tablename);
            } else {
                echo "SQL 쿼리 오류 >> ".$tablename;
                exit;
            }      
        } else {
            // 테이블 설정
            $this->_insert->setTablename($tablename);

            // 필드 컬럼 설정
            if ($fields) {
                $this->_insert->setFields($fields);
            }

            $this->clear(); // 초기화
        }


        // 객체반환
        return $this->_insert;
    }

    /**
     * SELECT 확장
     */
    private $_select;
    public function select($tablename, $fields=null)
    {
        // 플라이웨이트 공유객체 관리
        if (!isset($this->_select)) {
            // 객체를 생성합니다.
            $this->_select = new \Jiny\Mysql\Select($tablename, $this); 
        } 

        // raw쿼리 확인
        $q = \explode(" ",$tablename);
        if(isset($q[0]) && strtoupper($q[0]) == "SELECT") {
            // raw 쿼리 입력
            if(count($q)>3) {
                // raw 쿼리 설정
                // echo $tablename;
                $this->setQuery($tablename);
            } else {
                echo "SQL 쿼리 오류 >> ".$tablename;
                exit;
            }      
        } else {
            // 테이블 설정
            // echo $tablename;
            $this->_select->setTablename($tablename);

            // 필드 컬럼 설정
            if ($fields) {
                $this->_select->setFields($fields);
            }

            $this->clear(); // 초기화
        }

        // 객체반환
        return $this->_select;
    }

    /**
     * 갱신 확장
     */
    private $_update;
    public function update($tablename, $fields=null)
    {
        // 플라이웨이트 공유객체 관리
        if (!isset($this->_update)) {
            // 객체를 생성합니다.
            $this->_update = new \Jiny\Mysql\Update($tablename, $this); // 객체를 생성합니다.
        }

        // raw쿼리 확인
        $q = \explode(" ",$tablename);
        if(isset($q[0]) && strtoupper($q[0]) == "UPDATE") {
            // raw 쿼리 입력
            if(count($q)>3) {
                // raw 쿼리 설정
                // echo $tablename;
                $this->setQuery($tablename);
            } else {
                echo "SQL 쿼리 오류 >> ".$tablename;
                exit;
            }      
        } else {
            // 테이블 설정
            $this->_update->setTablename($tablename);

            // 필드 컬럼 설정
            if ($fields) {
                
                $this->_update->setFields($fields);
            }

            $this->clear(); // 초기화
        }

        return $this->_update;
    }

    /**
     * delete 테이블 확장
     */
    private $_delete; //delete 객체정보
    public function delete($tablename=null, $fields=null)
    {
        // 플라이웨이트 공유객체 관리
        if (!isset($this->_delete)) {
            // 객체를 생성합니다.
            $this->_delete = new \Jiny\Mysql\Delete($tablename, $this); // 객체를 생성합니다.
        }

        // raw쿼리 확인
        $q = \explode(" ",$tablename);
        if(isset($q[0]) && strtoupper($q[0]) == "DELETE") {
            // raw 쿼리 입력
            if(count($q)>3) {
                // raw 쿼리 설정
                // echo $tablename;
                $this->setQuery($tablename);
            } else {
                echo "SQL 쿼리 오류 >> ".$tablename;
                exit;
            }      
        } else {
            // 테이블 설정
            $this->_delete->setTablename($tablename);

            // 필드 컬럼 설정
            if ($fields) {
                $this->_delete->setFields($fields);
            }

            $this->clear(); // 초기화
        }



        return $this->_delete;
    }

    public function version()
    {
        return 0.6;
    }

    /**
     * 
     */
}