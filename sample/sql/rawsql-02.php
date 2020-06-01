<?php
require "../../loading.php"; // 오토로딩
$db = new \Jiny\Mysql\Connection();

// 데이터베이스 설정값
// 설정값, 생성자 인자값으로 전달합니다.
$dbinfo = \jiny\dbinfo();
$db = new \Jiny\Mysql\Connection($dbinfo);

// RawSQL 쿼리 예제
$query = "SHOW DATABASES";
$db->query($query); 
print_r($db->fetchObjAll());
