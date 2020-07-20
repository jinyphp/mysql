<?php
require "../../../../autoload.php";

// 데이터베이스 설정값
$dbinfo = \jiny\dbinfo();
$db = new \Jiny\Mysql\Connection($dbinfo);

/*
존재하지 않는 테이블을 읽어올때, 자동으로 테이블을 생성하빈다.
*/
$select = $db->select("members20")->autoTable();

// 데이터목록
if ($rows = $select->runObjAll()) {
    foreach($rows as $row) {
        foreach($row as $key => $value) {
            echo $key. "=". $value. "\t";
        }
        echo "\n";
    }
} else {
    echo "데이터목록이 없습니다.";
}

