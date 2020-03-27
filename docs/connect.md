# 데이터베이스 연결
데이터베이스 접속을 하는 방법에 대해서 설명을 합니다.  

## 드라이버
`jinydb`는 `PDO` 방식을 응용하여 데이터베이스에 접속을 합니다.  
PHP에서 PDO에 접속을 하기 위해서는 PDO 드라이버 활성화 해주어야 합니다. 
php.ini 파일, 912 라인줄 근처에서 다음과 같이 주석(`;`)을 제거합니다.   

```
extension=php_pdo_mysql.dll
```

드라이버가 활성화 되지 않으면, 코드에서 다음과 같은 오류를 출력 합니다.

```php
if (extension_loaded("PDO") && extension_loaded("pdo_mysql")) {
    // 접속코드
    // ...      
} else {
    echo "PDO 드라이버가 활성화 되어 있지 않습니다.\n";
    exit(1); // 오류 종료
}
```

## 초기화 방법1

예제코드: samples/conn-01.php

## 초기화 방법2

예제코드: samples/conn-02.php