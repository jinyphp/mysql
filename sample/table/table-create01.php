<?php
require "../../loading.php"; // 오토로딩

// 데이터베이스 설정값
$dbinfo = \jiny\dbinfo();

// 설정값, 생성자 인자값으로 전달합니다.
$db = new \Jiny\Mysql\Connection($dbinfo);

// 테이블 생성
$columns = [
'firstname' => "varchar(50)",
'lastname' => "varchar(100)",
];

$db->table("members4")->create($columns);

// 테이블 목록출력
print_r($db->table()->list());