# 목록출력

## Raw 쿼리를 이용한 목록출력

예제코드: select-01.php

## 출력 컬럼 선택
예제코드: select-02.php

예제코드: select-03.php



## 객체 확장하기
---
connection 객체는 데이터를 조작할 수 있는 select 객체를 확장합니다.
확장된 select 객체는 다양한 조건의 데이터를 조회할 수 있습니다.

### select 객체 얻기
select() 메소드 호출시 객체를 얻을 수 있습니다.

```php
$dataObj = $db->select("테이블명");
```

### 전체 데이터 조회
데이블의 전체 데이터를 조회 합니다.

```php
$dataObj->all();
```
all 메소드는 테이블의 전체 데이터를 조회 합니다.





