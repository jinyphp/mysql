<?php

// 지니모듈 오토로드
spl_autoload_register(function($className) {
    echo $className."을 로드 합니다.\n";
    
    // 클래스 파일명 변환
    $path = str_replace("\\", DIRECTORY_SEPARATOR, $className).".php";
    $path = __DIR__.DIRECTORY_SEPARATOR.$path;
    echo $path;

    exit;
});