<?php
echo "Hello World!";

$host = "mysql:";
$host .= "host=localhost";
$host .= ";dbname=shop";
$host .= ";charser=utf8";

$dbuser = "db2020";
$dbpassword = "123456";

try {
    $conn = new PDO($host, $dbuser, $dbpassword);
    echo "데이터 베이스 접속 성공!\n";
    
} catch (PDOException $e) {
    echo "접속 실패\n";
    echo $e->getMessage();
}