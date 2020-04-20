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
                // echo "데이터 베이스 접속 성공!\n";

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

        // 객체반환
        return $this->_schema;
    }

    /**
     * 테이블 확장
     */
    private $_table;
    public function table($tablename)
    {
        // 플라이웨이트 공유객체 관리
        if (!isset($this->_table)) {
            $this->_table = new \Jiny\Mysql\Table($tablename, $this); // 객체를 생성합니다.
        } else {
            $this->_table->setTablename($tablename); // 테이블을 재설정합니다.
        }

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
            echo "데이터 설정";
            $this->_insert->setFields($fields);
        } 

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
        if($fields) {
            $this->_select->setFields($fields);
        } 

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

        return $this->_update;
    }

    /**
     * 테이블 확장
     */
    private $_delete;
    public function delete($tablename)
    {
        // 플라이웨이트 공유객체 관리
        if (!isset($this->_delete)) {
            $this->_delete = new \Jiny\Mysql\Delete($tablename, $this); // 객체를 생성합니다.
        } else {
            $this->_delete->setTablename($tablename); // 테이블을 재설정합니다.
        }

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