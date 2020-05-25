# 스키마 데이터베이스
mysql은 다수의 스키마를 생성하여 관리를 할 수 있습니다. 

`schema` 클래스는 스키마를 관리할 수 있는 별도의 객체 입니다.
이 객체는 직접 생성을 할 수도 있으며, connect 객체의 `schema()` 메소드를 이용하여 동적 결합 할 수 있습니다.


## 객체 확장
다음은 connection 객체의 schema 코드 입니다.

```php
/**
 * 스키마 확장
 */
private $_schema;
public function schema()
{
    // 플라이웨이트 공유객체 관리
    if (!isset($this->_schema)) {
        $this->_schema = new \Jiny\Mysql\Schema($this); // 객체를 생성합니다.
    } else {
        
    }

    // 객체반환
    return $this->_schema;
}
```

coneection 클래스는 private 속성의 `$_schema`를 가지고 있습니다. schema() 메소드는 
플라이웨이트 패턴을 응용하여 Schema 객체를 확장합니다.  

`$_schema`에 객체가 존재하지 않으면 새로운 schema 객체를 생성합니다. 만일 이전에 한번이라도
schema() 메소드를 호출하여 객체를 생성한 적이 있다면, 이전에 생성된 객체를 반환 합니다.

스키마 객체를 사용하기 위해서는 schema() 메소드를 통하여 접근을 합니다. 
```php
$db->schema()->스키마메소드();
```

<br>

## 스키마 목록
객체를 확장을 하게 되면, 스키마 객체의 메소드를 사용할 수 있습니다.

```php
schema()->list($type=TRUE);
```

list() 메소드는 데이터베이스의 스키마 목록을 출력합니다.

매개변수를 `true`로 전달하면, 스키마의 목록만을 배열로 반환을 합니다.
`false`로 설정하면 컬럼 필드명을 포함한 2차원 배열로 전달 합니다.

기본값는 `true` 입니다.

## 테이블 갯수
스키마에 소속된 테이블의 갯수를 반환합니다.

```php
totalTables($schema);
```

## 스키마 확인

```php
is($schema)
```

입력한 스키마가 존재하는지를 확인 합니다.

## 스키마 생성
새로운 스키마를 생성합니다.

```php
create($name);
```

스키마가 존재하는지를 먼저 검사한 후에, 새로운 스키마를 생성합니다.
스키마의 중복 생성 오류를 방지 할 수 있습니다.

```php
isCreate($schema)
```


