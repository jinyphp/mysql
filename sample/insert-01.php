<?php
require "../loading.php"; // 오토로딩

// 데이터베이스 설정값
$dbinfo = include("../dbinfo.php");

// 설정값, 생성자 인자값으로 전달합니다.
$db = new \Jiny\Mysql\Database($dbinfo);

// RawSQL 데이터삽입
$query = "INSERT `shop`.`member5` SET firstname = 'lee', lastname='hojin';";
$db->query($query); 

if ($id = $db->conn->lastInsertId()) {
    echo "데이터 삽입 성공 = ".$id;
} else {
    echo "데이터 삽입 실패";
}

