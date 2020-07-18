<?php
require "../../loading.php"; // 오토로딩

// 데이터베이스 설정값
$dbinfo = \jiny\dbinfo();
$db = new \Jiny\Mysql\Connection($dbinfo);

$fields = ["id","firstname"];
$select = $db->select("members4")->setField("lastname");
$select->setWheres(["id"]);

$select->build($fields);
echo $db->getQuery();

$rows = $select->run(['id'=>"1"])->fetchObjAll();
print_r($rows);

