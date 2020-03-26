<?php
/*
 * jiny Modules.
 * (c) hojinlee <infohojin@gmail.com>
 */
namespace Modules\Databases;

class Database
{
    public function __construct()
    {
        $host = "mysql:";
        $host .= "host=localhost";
        $host .= ";dbname=shop";
        $host .= ";charser=utf8";

        $dbuser = "db2020";
        $dbpassword = "123456";

        // echo __CLASS__;
        if (extension_loaded("PDO") && extension_loaded("pdo_mysql")) {

            try {
                $conn = new \PDO($host, $dbuser, $dbpassword);
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