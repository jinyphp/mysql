<?php
require "../loading.php"; // 오토로딩

// 데이터베이스 설정값
$dbinfo = include("../dbinfo.php");

// 설정값, 생성자 인자값으로 전달합니다.
$db = new \Jiny\Mysql\Database($dbinfo);

// 테이블 생성
$columns = [
'firstname' => "varchar(50)",
'lastname' => "varchar(100)",
];

$db->tableCreate("member5", $columns);