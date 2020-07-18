<?php
require "../../loading.php"; // 오토로딩

// 데이터베이스 설정값
$dbinfo = \jiny\dbinfo();

// 설정값, 생성자 인자값으로 전달합니다.
$db = new \Jiny\Mysql\Connection($dbinfo);

// 스키마 목록
$schemaObj = $db->schema();
$rows = $schemaObj->list();
foreach ($rows as $name) {
    echo $name. "=";
    echo $schemaObj->totalTables($name). "\n";
    //print_r($row);
}
