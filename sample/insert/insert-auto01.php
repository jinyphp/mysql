<?php
require "../../loading.php"; // 오토로딩

// 데이터베이스 설정값
// 설정값, 생성자 인자값으로 전달합니다.
$dbinfo = \jiny\dbinfo();
$db = new \Jiny\Mysql\Connection($dbinfo);

/*
연상배열 값을 이용하여 데이터를 자동 삽입합니다.
방법3.
배열구조와 컬럼구조가 일치하지 않는 경우 오류가 발생합니다.
*/

// 데이터삽입
$data = [
    'firstname' => "이",
    'aaa' => "hello"
];

if ($id = $db->insert("members4")->save($data)) {
    echo "데이터 삽입 성공 = ".$id;
} else {
    echo "데이터 삽입 실패";
}

