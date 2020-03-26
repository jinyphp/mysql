<?php
require "loading.php"; // 오토로딩
$db = new \Modules\Databases\Database();
$db->setUser("db2020");
$db->setPassword("123456");
$db->connect();