<?php
require "../loading.php"; // 오토로딩

// 데이터베이스 설정값
// 설정값, 생성자 인자값으로 전달합니다.
$dbinfo = include("../dbinfo.php");
$db = new \Jiny\Mysql\Connection($dbinfo);

/**
 * 데이터 갯수 확인
 */
$count = $db->table("board")->count();
echo "데이터 갯수=".$count;
