<?php
require "../../loading.php"; // 오토로딩

// 데이터베이스 설정값
$dbinfo = \jiny\dbinfo();

// 설정값, 생성자 인자값으로 전달합니다.
$db = new \Jiny\Mysql\Connection($dbinfo);

// 데이터삽입
$data = [
    'firstname' => "이"
];

if ($id = $db->insert("members4", $data)->save()) {
    echo "데이터 삽입 성공 = ".$id;
} else {
    echo "데이터 삽입 실패";
}

