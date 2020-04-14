<?php
require "../loading.php"; // 오토로딩

// 데이터베이스 설정값
$dbinfo = include("../dbinfo.php");

// 설정값, 생성자 인자값으로 전달합니다.
$db = new \Jiny\Mysql\Connection($dbinfo);

// bind 데이터삽입
$query = "INSERT `shop`.`member5` SET firstname=:firstname, lastname=:lastname;";
$bind = [
    'firstname' => "이", 
    'lastname' => "호진"
];

if ($id = $db->insertBind($query, $bind)) {
    echo "데이터 삽입 성공 = ".$id;
} else {
    echo "데이터 삽입 실패";
}

