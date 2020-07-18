<?php
require "../../loading.php"; // 오토로딩

// 데이터베이스 설정값
$dbinfo = \jiny\dbinfo();

// 설정값, 생성자 인자값으로 전달합니다.
$db = new \Jiny\Mysql\Connection($dbinfo);

// 삭제 컬럼 (index array)
$fields = ['birth' ,'address'];

$db->table("members1")->dropColums($fields);

// 테이블 구조
$rows = $db->table()->desc("members1");
print_r($rows);