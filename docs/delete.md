# 삭제

## Raw 쿼리를 이용한 삭제

예제코드: delete-01.php

## bind 처리
예제코드: delete-02.php

## 클래스 확장
데이터를 삭제할 수 있는 delete 객체를 확장합니다.


### 객체얻기
```php
$dataObj = $db->delete("board");
```

### 특정 id 삭제

```php
$dataObj->id(5);
```

### 전체삭제
```php
$dataObj->all();
```

### 바인드 삭제
쿼리에 특정 조건값을 바인드 하여 삭제를 할 수 있습니다.

```php
$dataObj->bind($query, $bind);
```
