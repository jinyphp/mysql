<?php
require "../../loading.php"; // 오토로딩
require "../../src/Helpers/helper.php"; //헬퍼함수 로드

// 설정값
// 헬퍼함수 응용
$dbinfo = \jiny\dbinfo("../../dbinfo.php");

// 설정값, 
// 생성자 인자값으로 전달합니다.
$db = new \Jiny\Mysql\Connection($dbinfo);

// 연결
if ($conn = $db->connect()) {
    echo "데이터 접속 성공";
} else {
    echo "데이터 접속 실패";
}