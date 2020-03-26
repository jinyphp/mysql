<?php
require "loading.php"; // 오토로딩
$db = new \Modules\Databases\Database();
$db->setUser("db2020");
$db->setPassword("123456");
$db->setSchema("shop");
$db->setCharset(); // 기본값 사용
$db->setHost(); // 기본값 사용

// 데이터베이스 연결
$db->connect();