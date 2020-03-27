<?php
require "..\loading.php"; // 오토로딩

// 데이터베이스 설정값
$dbinfo = include("..\dbinfo.php");

// 설정값, 생성자 인자값으로 전달합니다.
$db = new \Modules\Databases\Database($dbinfo);

// 삭제 컬럼 (index array)
$fields = ['age' ,'address'];

$db->tableDropColums("member5", $fields);

// 테이블 구조
$rows = $db->tableDesc("member5");
print_r($rows);