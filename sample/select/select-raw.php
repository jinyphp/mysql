<?php
require "../../../../autoload.php";

// 데이터베이스 설정값
$dbinfo = \jiny\dbinfo();

// 설정값, 생성자 인자값으로 전달합니다.
$db = new \Jiny\Mysql\Connection($dbinfo);

// RawSQL 데이터삽입
$query = "SELECT * FROM `db2020`.`members4`;";
if ($rows = $db->query($query)->fetchObjAll()) {
    foreach($rows as $row) {
        foreach($row as $key => $value) {
            echo $key. "=". $value. "\t";
        }
        echo "\n";
    }
} else {
    echo "데이터목록이 없습니다.";
}

