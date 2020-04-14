<?php
require "../loading.php"; // 오토로딩

// 데이터베이스 설정값
$dbinfo = include("../dbinfo.php");

// 설정값, 생성자 인자값으로 전달합니다.
$db = new \Jiny\Mysql\Connection($dbinfo);

// 컬럼정보 변경
$fields = [
    'age'=>['birth'=>"date"]
];

$db->table("member5")->changeColums($fields);

// 테이블 구조
$rows = $db->table("member5")->desc();
print_r($rows);