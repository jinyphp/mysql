<?php
require "../../loading.php"; // 오토로딩

// 데이터베이스 설정값
$dbinfo = \jiny\dbinfo();

// 설정값, 생성자 인자값으로 전달합니다.
$db = new \Jiny\Mysql\Connection($dbinfo);

// 테이블 목록출력
$name = "members1";
if ($db->table()->is($name)) {
    echo $name. "테이블이 존재합니다.\n";
} else {
    echo $name. "테이블이 없습니다.\n";
}

