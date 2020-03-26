<?php
require "loading.php"; // 오토로딩
$db = new \Modules\Databases\Database();
$db->setPassword("123456");
$db->connect();