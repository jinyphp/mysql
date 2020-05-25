<?php
namespace Jiny\Mysql;

// 헬퍼함수
require __DIR__.DIRECTORY_SEPARATOR."src".DIRECTORY_SEPARATOR."Helpers".DIRECTORY_SEPARATOR."Helper.php";

// 지니모듈 오토로드
spl_autoload_register(function($className) {
    $name = \substr($className, strlen(__NAMESPACE__)); // 네임스페이스 중복경로 제거

    // 클래스 파일명 변환
    $path = "src".str_replace("\\", DIRECTORY_SEPARATOR, $name).".php";
    $path = __DIR__.DIRECTORY_SEPARATOR.$path;

    if(file_exists($path)) {
        require_once($path);
    } else {
        echo "Module loading: ".$path." file not found\n";
        exit;
    }
});