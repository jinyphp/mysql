# RawSQL 실행
데이터베이스 접속후에 rawSQL 쿼리를 실행할 수 있습니다.

## 쿼리전송

```php
// RawSQL 쿼리 예제
$query = "SHOW DATABASES";
$stmt = $db->query($query); 
```

PDOStatement 객체를 반환 받습니다. 

반환받은 객체에서 정보를 읽어 옵니다.  

* PDO::FETCH_ASSOC
* PDO::FETCH_OBJ

```php
while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
    print_r($row);
}
```

PDOStatement 는 fetch() 메소드를 이용하여 결과를 하나씩 읽어 옵니다. 더이상 반환할 데이터가 없는 경우 false 값을 전달 합니다.  

