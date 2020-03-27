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

