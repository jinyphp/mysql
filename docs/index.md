# jinyMysql
`jinyMysql`은 SQL 쿼리 빌더 입니다. 쿼리빌더를 이용하여 데이터베이스를 쉽게 조작할 수 있습니다.  

## 설치
컴포저 패키지를 통하여 설치를 할 수 있습니다.  
```
composer require jiny/mysql
```

패키지를 설치하게 되면, 메뉴얼(docs), 예제코도(sample), 소스코드(src)를 확인할 수 있습니다.

## 기능설명

* [연동 연결](./connect)
PDO 데이터베이스 연결을 처리합니다.  

* [raw쿼리](./rawsql)
SQL을 작성하여 명령을 실행합니다.

### 데이터베이스와 스키마
* [database](./database)
* [schema](./schema)
* [테이블](./table)

### CRUD
* [삽입](./insert)
* [목록](./select)
* [업데이트](./update)
* [삭제](./delete)

