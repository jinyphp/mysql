<?php
/*
 * jiny Modules.
 * (c) hojinlee <infohojin@gmail.com>
 */
namespace Modules\Databases;

class Database
{
    // 데이터베이스 접속정보
    private $host;
    private $dbuser;
    private $sdpassword;

    public function __construct()
    {
        $this->host = "mysql:";
    }

    /**
     * 데이터베이스 접속처리 루틴
     */
    public $conn;
    public function connect()
    {
        if (extension_loaded("PDO") && extension_loaded("pdo_mysql")) {

            try {
                $this->conn = new \PDO($this->host, $this->dbuser, $this->dbpassword);
                echo "데이터 베이스 접속 성공!\n";
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
        $this->host .= ";dbname=".$schema;
        return $this; // 메서드체인
    }

    /**
     * 문자셋 설정
     */
    public function setCharset($charset="utf8")
    {
        $this->host .= ";charset=".$charset;
        return $this; // 메서드체인
    }

    /**
     * 호스트 설정
     */
    public function setHost($host="localhost")
    {
        $this->host .= ";host=".$host;
        return $this; // 메서드체인
    }

}