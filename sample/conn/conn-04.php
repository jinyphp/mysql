<?php
require "../../loading.php"; // 오토로딩

// 데이터베이스 설정값
$dbinfo = \jiny\dbinfo();

// 설정값, 생성자 인자값으로 전달합니다.
$db = new \Jiny\Mysql\Connection($dbinfo);

// 데이터베이스 연결
$conn = $db->connect();
if ($conn) {
    echo "데이터베이스 접속 성공\n";
} else {
    echo "데이터베이스 접속 실패\n";
}