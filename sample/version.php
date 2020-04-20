<?php
require "../loading.php"; // 오토로딩

$db = new \Jiny\Mysql\Connection();
echo $db->version();
