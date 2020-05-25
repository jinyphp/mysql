# RawSQL 실행
---
생성된 DB 컨넥터를 이용하여 SQL 쿼리를 실행 합니다.

<br>

## query
---
PDO를 활용하여 쿼리를 직접 전송할 수 있습니다.

connect 클래스는 raw 쿼리를 직접 db에 전송할 수 있는 `query()`메소드를 제공합니다.

```php
query(쿼리);
```

쿼리를 실행하게 되면 PDO Statement 객체를 반환 받습니다.

```php
// RawSQL 쿼리 예제
$query = "SHOW DATABASES";
$stmt = $db->query($query); 
```

### 체인연결
query 메소드를 실행후에 메서드 체인으로 바로 fetch 메소드를 호출할 수 있습니다.

<br>

### PDOStatement 읽기
---
RawSQL 쿼리를 실행후에 직전의 PDOStatement 값을 읽을 수 있습니다.  

```php
$stmt = $db->statement();
```

<br>

## 결과확인
---
실행되는 쿼리가 결과 값을 반환 받는 경우에는 PDOStatement 객체를 통해서 
결과 값을 가질 수 있습니다.

PDOStatement 는 fetch() 메소드를 이용하여 결과를 하나씩 읽어 옵니다. 더이상 반환할 데이터가 없는 경우 false 값을 전달 합니다.  

```php
while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
    print_r($row);
}
```

PDO의 `fetch()`메소드는 반환되는 결과값의 타입을 지정할 수 있는 상수를 전달합니다.

* PDO::FETCH_ASSOC
결과값을 배열로 반환 합니다.

* PDO::FETCH_OBJ
결과값을 객체로 반환 합니다.

<br>

## 데이터 가지고 오기
---
전송한 쿼리의 결과값을 쉽게 가지고 올 수 있도록 ASSOC, OBJ 메소드를 제공합니다.

<br>

### 객체 반환
---
전송한 쿼리의 결과값을 객체로 가지고 올 수 있도록 2개의 메소드를 제공합니다.

* fetchObj
* fetchObjAll

#### fetchObj
`fetchObj()`는 query() 메소드로 전송된 결과값을 객체로 하나의 데이터를 읽어 옵니다.

#### fetchObjAll
만일 데이터가 여러개가 있는 경우 `fetchObj()`를 결과값의 횟수 만큼 호출을 하여야 합니다. 
여러 데이터를 읽을 수 있도록 `fetchObjAll()` 메소드를 제공합니다.

예제코드: rawsql-02.php
```php
// RawSQL 쿼리 예제
$query = "SHOW DATABASES";
$db->query($query); 
print_r($db->fetchObjAll());
```

### 배열 반환
---
PDO::FETCH_ASSOC 옵션을 이용하여 결과값을 반환 받습니다.

#### fetchAssoc
`fetchAssoc()`는 실행된 sql의 결과값을 배열 타입으로 읽어 옵니다.

#### fetchAssocAll
`fetchAssocAll()`은 여러개의 데이터값을 읽어 옵니다.

<br>





