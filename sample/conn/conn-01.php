<?php
require "../../loading.php"; // 오토로딩

$db = new \Jiny\Mysql\Connection();

// 메서드 체인으로 DB정보 설정
$db->setUser("db2020");
$db->setPassword("123456");
$db->setSchema("db2020");
$db->setCharset();
$db->setHost(); // 기본값 사용

// 데이터베이스 연결
$conn = $db->connect();
if ($conn) {
    echo "데이터베이스 접속 성공\n";
} else {
    echo "데이터베이스 접속 실패\n";
}