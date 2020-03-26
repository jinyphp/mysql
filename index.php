<?php
require "loading.php"; // 오토로딩

// 데이터베이스 설정값
$dbinfo = [
    'user'=>"db2020",
    'password'=>"123456",
    'schema'=>"shop"
];

// 설정값, 생성자 인자값으로 전달합니다.
$db = new \Modules\Databases\Database($dbinfo);

// 메서드 체인으로 설정
// $db->setUser("db2020")->setPassword("123456")->setSchema("shop")->setCharset()->setHost(); // 기본값 사용

// 데이터베이스 연결
$conn = $db->connect();