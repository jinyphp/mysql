<?php
require "../../loading.php"; // 오토로딩

// 데이터베이스 설정값
$dbinfo = \jiny\dbinfo();

// 설정값, 생성자 인자값으로 전달합니다.
$db = new \Jiny\Mysql\Connection($dbinfo);

// bind 데이터삽입
$query = "INSERT `db2020`.`members4` SET firstname=:firstname, lastname=:lastname;";
$db->setQuery($query);

$data = [
    'firstname' => "이", 
    'lastname' => "호진"
];
$db->setBinds($data);
$db->run();

if ($id = $db->conn()->lastInsertId()) {
    echo "데이터 삽입 성공 = ".$id;
} else {
    echo "데이터 삽입 실패";
}


