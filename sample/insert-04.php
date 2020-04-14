<?php
require "../loading.php"; // 오토로딩

// 데이터베이스 설정값
// 설정값, 생성자 인자값으로 전달합니다.
$dbinfo = include("../dbinfo.php");
$db = new \Jiny\Mysql\Connection($dbinfo);

// 데이터삽입
$data = [
    'name' => "호진"
];

$last = $db->insert("board")->setFields($data)->save();
echo "마지막 삽입=".$last;

$data = [
    'name' => "지니"
];

$last = $db->insert("board")->save($data);
echo "마지막 삽입=".$last;

