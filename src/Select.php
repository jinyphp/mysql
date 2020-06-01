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
     * 설정된 쿼리를 실행합니다.
     */
    public function run($data=null)
    {
        return $this->_db->run($data);
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
        if (!$this->_db->getQuery()) {
            $query = $this->build($this->_fields); // 쿼리 생성
        }
        return $this->_db->run($data)->fetchObjAll();
    }

    public function runObj($data=null)
    {
        if (!$this->_db->getQuery()) {
            $query = $this->build($this->_fields); // 쿼리 생성
        }
        return $this->_db->run($data)->fetchObj();
    }

    public function runAssocAll($data=null)
    {
        if (!$this->_db->getQuery()) {
            $query = $this->build($this->_fields); // 쿼리 생성
        }
        return $this->_db->run($data)->fetchAssocAll();
    }

    public function runAssoc($data=null)
    {
        if (!$this->_db->getQuery()) {
            $query = $this->build($this->_fields); // 쿼리 생성
        }
        return $this->_db->run($data)->fetchAssoc();
    }

    /**
     * 
     */
}