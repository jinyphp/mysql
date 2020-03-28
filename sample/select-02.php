<?php
require "../loading.php"; // 오토로딩

// 데이터베이스 설정값
$dbinfo = include("../dbinfo.php");

// 설정값, 생성자 인자값으로 전달합니다.
$db = new \Jiny\Mysql\Database($dbinfo);

// 데이터목록
if ($rows = $db->select("member5")->fetchObjAll()) {
    foreach($rows as $row) {
        foreach($row as $key => $value) {
            echo $key. "=". $value. "\t";
        }
        echo "\n";
    }
} else {
    echo "데이터목록이 없습니다.";
}

