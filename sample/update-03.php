<?php
require "../loading.php"; // 오토로딩

// 데이터베이스 설정값
// 설정값, 생성자 인자값으로 전달합니다.
$dbinfo = include("../dbinfo.php");
$db = new \Jiny\Mysql\Connection($dbinfo);

// 데이터갱신
$db->update("board")->setFields(['name'=>"1234"])->id(1);
/*
$db->update("board")
$query = "UPDATE `shop`.`member5` SET firstname=:firstname, lastname=:lastname where id=:id;";
$db->update("member5")->bind($query,[
    'id'=>5,
    'firstname'=>"jiny",
    'lastname'=>"lee"
]);
*/

if ($rows = $db->select("board")->fetchObjAll()) {
    foreach($rows as $row) {
        foreach($row as $key => $value) {
            echo $key. "=". $value. "\t";
        }
        echo "\n";
    }
} else {
    echo "데이터목록이 없습니다.";
}

