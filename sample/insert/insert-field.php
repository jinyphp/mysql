<?php
require "../../loading.php"; // 오토로딩

// 데이터베이스 설정값
$dbinfo = \jiny\dbinfo();
$db = new \Jiny\Mysql\Connection($dbinfo);

// 데이터삽입
$data = [
    'lastname' => "호진"
];

$last = $db->insert("members4")->setFields($data)->save();
echo "마지막 삽입=".$last;


