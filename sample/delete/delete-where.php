<?php
require "../../loading.php"; // 오토로딩

// 데이터베이스 설정값
$dbinfo = \jiny\dbinfo();
$db = new \Jiny\Mysql\Connection($dbinfo);

$delete = $db->delete("members4");
$delete->setWhere("id")->build()->run(['id'=>29]);
echo $db->getQuery()."\n";

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