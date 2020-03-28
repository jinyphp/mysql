<?php
require "..\loading.php"; // 오토로딩

// 데이터베이스 설정값
$dbinfo = include("..\dbinfo.php");

// 설정값, 생성자 인자값으로 전달합니다.
$db = new \Modules\Databases\Database($dbinfo);

// RawSQL 데이터삽입
$query = "SELECT * FROM `shop`.`member5`;";
if ($rows = $db->query($query)->fetchObjAll()) {
    foreach($rows as $row) {
        foreach($row as $key => $value) {
            echo $key. "=". $value. "\t";
        }
        echo "\n";
    }
} else {
    echo "데이터목록이 없습니다.";
}
