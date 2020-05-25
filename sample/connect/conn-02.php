<?php
require "../../loading.php"; // 오토로딩

// 데이터베이스 설정값
$dbinfo = include("../../dbinfo.php");

// 설정값, 생성자 인자값으로 전달합니다.
$db = new \Jiny\Mysql\Connection($dbinfo);

// 연결
if ($conn = $db->connect()) {
    echo "데이터 접속 성공";
} else {
    echo "데이터 접속 실패";
}