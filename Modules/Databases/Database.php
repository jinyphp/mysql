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
        $this->host .= "host=localhost";
        $this->host .= ";dbname=shop";
        $this->host .= ";charser=utf8";

        $this->dbuser = "db2020";
        $this->dbpassword = "123456";

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
}