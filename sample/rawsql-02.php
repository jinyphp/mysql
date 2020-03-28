<?php
require "../loading.php"; // 오토로딩

// 데이터베이스 설정값
$dbinfo = include("../dbinfo.php");

// 설정값, 생성자 인자값으로 전달합니다.
$db = new \Jiny\Mysql\Database($dbinfo);

// RawSQL 쿼리 예제
$query = "SHOW DATABASES";
$db->query($query); 
print_r($db->fetchObjAll());
