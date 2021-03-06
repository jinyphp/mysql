<?php
require "../../loading.php"; // 오토로딩

// 데이터베이스 설정값
$dbinfo = \jiny\dbinfo();

// 설정값, 생성자 인자값으로 전달합니다.
$db = new \Jiny\Mysql\Connection($dbinfo);

// 테이블 생성
$addFields = [
    'age' => "int(10)",
    'address' => "varchar(100)",
];

$db->table("members1")->addColums($addFields);

// 테이블 구조
$rows = $db->table("members1")->desc();
print_r($rows);