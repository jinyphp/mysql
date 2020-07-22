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

use Jiny\Mysql\Database;

// 데이터 조회
class Select extends Database
{
    use Where; //trait 연결

    public function __construct($tablename, $db)
    {
        $this->_tablename = $tablename;
        $this->_db = $db;
        $this->_schema = $db->getSchema();

        // db접속 상태를 확인
        if (!$this->_db->conn()) $this->_db->connect(); 
    }

    /**
     * 전체 데이터를 출력합니다.
     */
    public function all()
    {
        //쿼리 생성
        $query = "SELECT * ";
        $query .= " FROM `".$this->_schema."`.`".$this->_tablename."`;";
        $this->_db->setQuery($query);

        return $this->_db->run()->fetchObjAll();
    }

    /**
     * 쿼리문을 빌드합니다.
     */
    private function queryBuild($fields)
    {
        //쿼리 생성
        $query = "SELECT ";
        if(is_array($fields) && count($fields)) {
            // 선택필드
            foreach ($fields as $value) {
                $query .= $value.",";
            }
            $query = rtrim($query,','); // 마지막 콤마 제거
       
        } else {
            $query .= "*"; // 전체목록
        }
        $query .= " FROM `".$this->_schema."`.`".$this->_tablename."`";

        return $query;
    }

    /**
     * 데이터 갯수확인
     */
    public function count($where=null)
    {
        $query = "SELECT count(id) from ".$this->_tablename;

        $this->_db->query($query);
        $this->_db->statement()->execute();
        $num = $this->_db->fetchAssoc();

        return $num['count(id)'];
    }

    /**
     * 데이터 읽기
     */
    public function runObjAll($data=null)
    {
        //echo __METHOD__;
        if (!$this->_db->getQuery()) {
            //echo "쿼리빌드\n";
            // 템플릿 메서드 : 쿼리 생성
            $this->build($this->_fields);
        }
       
        $obj = $this->run($data);
        return $obj->fetchObjAll();
    }


    public function runObj($data=[])
    {
        if (!$this->_db->getQuery()) {
            $query = $this->build($this->_fields); // 쿼리 생성
        }
        return $this->run($data)->fetchObj();
    }

    public function runAssocAll($data=[])
    {
        if (!$this->_db->getQuery()) {
            $query = $this->build($this->_fields); // 쿼리 생성
        }
        return $this->run($data)->fetchAssocAll();
    }

    public function runAssoc($data=[])
    {
        if (!$this->_db->getQuery()) {
            $query = $this->build($this->_fields); // 쿼리 생성
        }
        return $this->run($data)->fetchAssoc();
    }

    /**
     * 단일 데이터 반환
     */
    public function find($id){ return $this->id($id); } // alias
    public function id($id)
    {
        if(\is_numeric($id)) {
            $this->where("id=:id")->build();
            return $this->run(['id'=>$id])->fetchAssoc();
        }        
    }

    /**
     * 
     */
}