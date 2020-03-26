<?php
echo "Hello World!";

$host = "mysql:";
$host .= "host=localhost";
$host .= ";dbname=shop";
$host .= ";charser=utf8";

$dbuser = "db2020";
$dbpassword = "123456";

$conn = new PDO($host, $dbuser, $dbpassword);