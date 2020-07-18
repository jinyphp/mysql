<?php
require "../../loading.php"; // 오토로딩

// 데이터베이스 설정값
$dbinfo = \jiny\dbinfo();
$db = new \Jiny\Mysql\Connection($dbinfo);

$fields = ["id","firstname"];
$query = $db->select("members4")->build($fields)->getQuery();
echo $query;


