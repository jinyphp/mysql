<?php
require "../loading.php"; // 오토로딩

// 데이터베이스 설정값
$dbinfo = include("../dbinfo.php");

// 설정값, 생성자 인자값으로 전달합니다.
$db = new \Jiny\Mysql\Database($dbinfo);

// 스키마 생성
$db->schemaCreate("jinyshop");

// 스키마 목록
$rows = $db->schemaList();
print_r($rows);