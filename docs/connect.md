# 데이터베이스 연결
---
데이터베이스 접속을 하는 방법에 대해서 설명을 합니다.  

<br>

## 드라이버
---
`PDO` 방식을 응용하여 데이터베이스에 접속을 합니다.  
PHP에서 PDO에 접속을 하기 위해서는 PDO 드라이버 활성화 해주어야 합니다. 
php.ini 파일, `912` 라인줄 근처에서 다음과 같이 주석(`;`)을 제거합니다.   

```ini
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

<br>

## 객체생성 및 설정
DB 컨넥트를 위한 Connection 클래스의 객체를 생성합니다.

```php
// 데이터베이스 컨넥션을 생성합니다.
$db = new \Jiny\Mysql\Connection();
```

생성된 객체는 단순한 PDO 컨텍트를 위한 준비된 객체 입니다. 
실제 DB접속을 하기 위해서는 설정 정보를 입력해 주어야 합니다.

## DB 접속정보 설정하기
DB 접속정보는 코드상에서 작성 또는 외부 파일을 이용하여 설정할 수 있습니다.

### 코드설정
코드를 통하여 값을 설정합니다.

```php
// 접속정보 설정
$db->setUser("db2020");
$db->setPassword("123456");
$db->setSchema("shop");
$db->setCharset();
$db->setHost(); 
```

메서드 체인
```php
// 메서드 체인설정
$db->setUser("db2020")->setPassword("123456")->setSchema("shop")->setCharset()->setHost();
```

<br>

### 외부파일
외부 설정파일을 통하여 DB접속 정보를 초기화 할 수 있습니다.

dbinfo.php
```php
<?php
return [
    'user'=>"db2020",
    'password'=>"123456",
    'schema'=>"shop",
    'host'=>"localhost",
    'charset'=>"utf8"
];
```

include 를 통하여 설정값을 읽어 옵니다.

```php
// 데이터베이스 설정값
$dbinfo = include("../../dbinfo.php");
```

헬퍼함수 사용

```php
// 헬퍼함수 응용
$dbinfo = \jiny\dbinfo("../../dbinfo.php");
```

<br>

### 예제코드
첨부된 예제코드를 통하여 동작을 확인해 볼 수 있습니다.

## 초기화 방법1
---
예제코드: samples/connect/conn-01.php

```php
<?php
require "../../loading.php"; // 오토로딩

// 데이터베이스 컨넥션을 생성합니다.
$db = new \Jiny\Mysql\Connection();

// 접속정보 설정
// 메서드 체인설정
$db->setUser("db2020")->setPassword("123456")->setSchema("shop")->setCharset()->setHost(); // 기본값 사용

// 연결
if ($conn = $db->connect()) {
    echo "데이터 접속 성공";
} else {
    echo "데이터 접속 실패";
}
```

### 초기화 방법2

예제코드: samples/connect/conn-02.php
```php
<?php
require "../../loading.php"; // 오토로딩

// 데이터베이스 설정값
$dbinfo = include("../../dbinfo.php");

// 설정값, 생성자 인자값으로 전달합니다.
$db = new \Jiny\Mysql\Connection($dbinfo);

// 연결
if ($conn = $db->connect()) {
    echo "데이터 접속 성공";
} else {
    echo "데이터 접속 실패";
}
```

### 초기화 방법3
예제코드: samples/connect/conn-03.php

```php
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
```


