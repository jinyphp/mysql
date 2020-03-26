<?php

// 지니모듈 오토로드
spl_autoload_register(function($className) {
    echo $className."을 로드 합니다.\n";
    exit;
});