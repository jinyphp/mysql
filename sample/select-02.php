<?php
require "../loading.php"; // 오토로딩

// 데이터베이스 설정값
// 설정값, 생성자 인자값으로 전달합니다.
$dbinfo = include("../dbinfo.php");
$db = new \Jiny\Mysql\Connection($dbinfo);

// 데이터목록
if ($rows = $db->select("board")->fetchObjAll()) {
    foreach($rows as $row) {
        foreach($row as $key => $value) {
            echo $key. "=". $value. "\t";
        }
        echo "\n";
    }
} else {
    echo "데이터목록이 없습니다.";
}

