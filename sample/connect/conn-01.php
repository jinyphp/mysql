<?php
require "../../loading.php"; // 오토로딩

// 데이터베이스 컨넥션을 생성합니다.
$db = new \Jiny\Mysql\Connection();

// 접속정보 설정
// 메서드 체인설정
$db->setUser("db2020")->setPassword("123456")->setSchema("shop")->setCharset()->setHost(); // 기본값 사용

// 연결
if ($conn = $db->connect()) {
    echo "데이터 접속 성공";
} else {
    echo "데이터 접속 실패";
}