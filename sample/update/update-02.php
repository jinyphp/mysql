<?php
require "../../loading.php"; // 오토로딩

// 데이터베이스 설정값
$dbinfo = \jiny\dbinfo();

// 설정값, 생성자 인자값으로 전달합니다.
$db = new \Jiny\Mysql\Connection($dbinfo);

// 데이터갱신
$query = "UPDATE `db2020`.`members4` SET firstname=:firstname, lastname=:lastname where id=:id;";
$db->update("member5")->binds($query,[
    'id'=>2,
    'firstname'=>"333",
    'lastname'=>"444"
]);

if ($rows = $db->select("members4")->runObjAll()) {
    foreach($rows as $row) {
        foreach($row as $key => $value) {
            echo $key. "=". $value. "\t";
        }
        echo "\n";
    }
} else {
    echo "데이터목록이 없습니다.";
}

