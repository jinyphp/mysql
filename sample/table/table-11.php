<?php
require "../../loading.php"; // 오토로딩

// 데이터베이스 설정값
$dbinfo = \jiny\dbinfo();
$db = new \Jiny\Mysql\Connection($dbinfo);

/**
 * 데이터 갯수 확인
 */
$count = $db->table("members4")->count();
echo "데이터 갯수=".$count;
