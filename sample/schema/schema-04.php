<?php
require "../../loading.php"; // 오토로딩

// 데이터베이스 설정값
$dbinfo = \jiny\dbinfo();

// 설정값, 생성자 인자값으로 전달합니다.
$db = new \Jiny\Mysql\Connection($dbinfo);

// 스키마 목록
$schemaObj = $db->schema();
$name = "db2020";
if ($schemaObj->is($name)) {
    echo $name. "스키마가 존재합니다.\n";
} else {
    echo $name. "스키마가 없습니다.\n";
}