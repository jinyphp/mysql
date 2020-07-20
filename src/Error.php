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

class Error
{
    private $_db;
    public function __construct($db)
    {
        // 깊은복사: 프로퍼티 패턴
        $this->_db = clone $db;
    }

    public function error($e, $obj)
    {
        switch($e->getCode()){
            case '42S02':
                if($obj->autoset() & 0x01) {
                    // 테이블 생성
                    $this->error42S02($obj);
                } else {
                    $this->exit($e);
                }                
                break;

            case '42S22':
                // 컬럼매칭 오류
                if($obj->autoset() & 0x02) {
                    $this->error42S22($obj);
                } else {
                    $this->exit($e);
                }                
                break;

            default:
                $this->exit($e);
                exit;
        }
    }

    private function exit($e)
    {
        echo "데이터베이스 처리 오류 ".$e->getCode()."\n";
        echo $e->getMessage();
        exit;
    }

    /**
     * 컬럼불일치
     */
    private function error42S22($obj)
    {
        // 컬럼필드 생성
        $field = [];
        $arr = $obj->fields();

        // 컬럼 필드 정보를 읽어 옵니다.
        $desc = $this->_db->table($obj->getTablename())->desc();;

        // 배열비교 m x m           
        foreach(array_keys($arr) as $key) {
            $exist = false;
            for($i=0;$i<count($desc);$i++) {
                if($key == $desc[$i]['Field']) $exist = true;
            }
            if(!$exist) {
                $this->addColum($obj->getTablename(), $key);
            }          
        }
    }

    private function addColum($table, $key)
    {
        // 컴럼추가
        $this->_db->table($table)->addColums([$key => "text"]);
    }

    /**
     * 테이블이 존재하지 않는 경우
     */
    private function error42S02($obj)
    {
        // 컬럼필드 생성
        $field = [];
        $arr = $obj->fields();
        if (\is_array($arr) && \array_keys($arr) !== range(0, count($arr) - 1)) {
            // 연상배열
            foreach($arr as $key => $value) {
                $field[$key] = "text";
            }
        } else {
            // 순차배열
            foreach($arr as $key) {
                $field[$key] = "text";
            }
        }

        $this->_db->table( $obj->getTablename() )->create($field);
    }
    //
}