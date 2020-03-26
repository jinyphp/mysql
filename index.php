<?php
require "loading.php"; // 오토로딩
$db = new \Modules\Databases\Database();

// 메서드 체인으로 설정
$db->setUser("db2020")->setPassword("123456")->setSchema("shop")->setCharset()->setHost(); // 기본값 사용

// 데이터베이스 연결
$db->connect();