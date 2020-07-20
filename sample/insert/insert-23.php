<?php
require "../../../../autoload.php";

// 데이터베이스 설정값
// 설정값, 생성자 인자값으로 전달합니다.
$dbinfo = \jiny\dbinfo();
$db = new \Jiny\Mysql\Connection($dbinfo);

/*
연상배열 값을 이용하여 데이터를 자동 삽입합니다.
방법2.
*/

// 데이터삽입
$data = [
    'firstname2' => "이"
];

if ($id = $db->insert("members21")->autoField()->save($data)) {
    echo "데이터 삽입 성공 = ".$id;
} else {
    echo "데이터 삽입 실패\n";
}

if ($rows = $db->select("members21")->runObjAll()) {
    foreach($rows as $row) {
        foreach($row as $key => $value) {
            echo $key. "=". $value. "\t";
        }
        echo "\n";
    }
} else {
    echo "데이터목록이 없습니다.";
}