<?php
require "..\loading.php"; // 오토로딩

// 데이터베이스 설정값
$dbinfo = include("..\dbinfo.php");

// 설정값, 생성자 인자값으로 전달합니다.
$db = new \Modules\Databases\Database($dbinfo);

// RawSQL 쿼리 예제
// 테이블 생성쿼리
$query = "CREATE TABLE `shop`.`member1` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    primary key(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$db->query($query); 

