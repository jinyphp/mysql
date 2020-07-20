# 데이터베이스
Table 및 CRUD 작업은 테이블을 기반으로 작업이 이루어 집니다. 
테이블의 정보는 모든 클래스에 공통적으로 적용되는 부분입니다.

## 추상화
공통적으로 적용되는 부분은 추상화를 통하여 하위 클래스에게 위임을 합니다.

```php
<?php
/*
 * This file is part of the jinyPHP package.
 *
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Jiny\Mysql;

abstract class Database
{
    protected $_tablename;
    protected $_schema;
    protected $_db;

    public function setTablename($tablename)
    {
        $this->_tablename = $tablename;
        return $this;
    }

    public function getTablename()
    {
        return $this->_tablename;
    }

    public function setSchema($schema)
    {
        $this->_schema = $schema;
        return $this;
    }

    public function getSchema()
    {
        return $this->_schema;
    }
}
```

데이터베이스 클래스의 주요 역할은 공통적인 프로퍼티와 getter/setter 메소드 입니다.

## 상속적용
예를 들어 다음과 같이 추상클래스를 상속합니다.

```php
// 테이블 관련작업 클래스
class Table extends Database
{
}
```

