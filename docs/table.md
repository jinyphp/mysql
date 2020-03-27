# 테이블 생성
데이터베이스 테이블을 생성합니다.

## Raw 쿼리를 이용한 생성
create 명령을 사용하여 직접 테이블을 생성할 수 있습니다.

```php
// 테이블 생성쿼리
$query = "CREATE TABLE `shop`.`member1` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    primary key(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$db->query($query); 
```

## 테이블 골력생성

tableCreateEmpty 메소드는 기본 골력의 테이블을 생성합니다.  

```php
// 테이블 생성
$db->tableCreateEmpty("member2");
```

테이블 기본 골력은 다음과 같은 필드만 포함합니다.
* id
* created_at
* updated_at

## 테이블 목록
테이블 목록을 출력합니다. 반환값은 array 입니다.

```php
public tableList() : array
```

예제코드: table-03.php

## 테이블 확인

테이블이 존재하는지 확인을 할 수 있습니다.

```php
public isTable($name) : bool
```

예제코드: table-04.php

## 테이블 생성
요청한 구조의 테이블을 생성할 수 있습니다.  
테이블은 컬럼명과 필드속성을 한쌍을 가지고 있습니다.  

추가되는 테이블을 연상배열로 설정을 합니다.

예제코드: table-05.php

```php
// 테이블 생성
$columns = [
'firstname' => "varchar(50)",
'lastname' => "varchar(100)",
];

$db->tableCreate("member5", $columns);
```

두번째 인자값으로 컬럼정보의 배열을 전달합니다. 

## 테이블 구조
테이블의 구조를 확인할 수 있습니다.

```php
public function tableDesc($name) : array
```

예제코드: table-06.php

## 테이블 필드 추가
생성한 테이블에 필드를 추가할 수 있습니다.

```php
public function tableAddColums($name,$columns)
```

예제코드: table-07.php

## 테이블 수정

예제코드: table-08.php

## 테이블 삭제

예제코드: table-09.php