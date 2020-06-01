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
    // 데이터베이스 접속정보
    private $host = "localhost";
    private $dbuser;
    private $dbpassword;
    private $charset="utf8";
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
        if (!$this->_conn) $this->connect(); // db접속 상태를 확인
        
        $this->stmt = null; // 초기화
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
        if (!$this->_conn) $this->connect(); // db접속 상태를 확인  
        if (!$this->stmt) {
            $this->stmt = $this->_conn->prepare($this->_query); // 쿼리문을 준비합니다.
        }
        $this->stmt->bindParam(':'.$key, $value);
        return $this;
    }

    /**
     * 설정된 쿼리를 실행합니다.
     */
    public function run($data=null)
    {
        if (!$this->_conn) $this->connect(); // db접속 상태를 확인
        if (!$this->stmt) {
            // 쿼리문을 준비합니다.
            if ($data) {
                $this->setBinds($data);
            } else {
                $this->stmt = $this->_conn->prepare($this->_query); 
            }
        }

        $this->stmt->execute();
        return $this;
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
        // 플라이웨이트 공유객체 관리
        if (!isset($this->_table)) {
            $this->_table = new \Jiny\Mysql\Table($tablename, $this); // 객체를 생성합니다.

        } else {
            if ($tablename) {
                $this->_table->setTablename($tablename); // 테이블을 재설정합니다.
            } 

        }

        $this->_query = null;
        $this->stmt = null;

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
            $this->_insert = new \Jiny\Mysql\Insert($tablename, $this); // 객체를 생성합니다.
        } else {
            $this->_insert->setTablename($tablename); // 테이블을 재설정합니다.
        }

        // 조회값 설정
        if($fields) {
            // echo "데이터 설정";
            $this->_insert->setFields($fields);
        }
        
        $this->_query = null;
        $this->stmt = null;

        // 객체반환
        return $this->_insert;
    }

    /**
     * 데이터 목록 확장
     */
    private $_select;
    public function select($tablename, $fields=null)
    {
        // 플라이웨이트 공유객체 관리
        if (!isset($this->_select)) {
            $this->_select = new \Jiny\Mysql\Select($tablename, $this); // 객체를 생성합니다.
        } else {
            $this->_select->setTablename($tablename); // 테이블을 재설정합니다.
        }

        // 조회값 설정
        if ($fields) {
            $this->_select->setFields($fields);
        }

        $this->_query = null;
        $this->stmt = null;

        // 객체반환
        return $this->_select;
    }

    /**
     * 갱신 확장
     */
    private $_update;
    public function update($tablename)
    {
        // 플라이웨이트 공유객체 관리
        if (!isset($this->_update)) {
            $this->_update = new \Jiny\Mysql\Update($tablename, $this); // 객체를 생성합니다.
        } else {
            $this->_update->setTablename($tablename); // 테이블을 재설정합니다.
        }

        $this->_query = null;
        $this->stmt = null;

        return $this->_update;
    }

    /**
     * 테이블 확장
     */
    private $_delete;
    public function delete($tablename=null)
    {
        // 플라이웨이트 공유객체 관리
        if (!isset($this->_delete)) {
            $this->_delete = new \Jiny\Mysql\Delete($tablename, $this); // 객체를 생성합니다.
        } else {
            if($tablename) {
                $this->_delete->setTablename($tablename); // 테이블을 재설정합니다.
            }            
        }

        $this->_query = null;
        $this->stmt = null;

        return $this->_delete;
    }

    public function version()
    {
        return 0.1;
    }

    /**
     * 
     */
}