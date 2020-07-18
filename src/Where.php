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

trait Where
{
    private $_wheres=[];
    public function setWhere($where)
    {
        \array_push($this->_wheres, $where);
        return $this;
    }

    public function setWheres($wheres=[])
    {
        // 일반배열
        foreach($wheres as $w) {
            $this->setWhere($w);
        }

        // 연상배열


        return $this;
    }

    private function queryWhere()
    {
        if (count($this->_wheres)>0) {
            $where = "";
            foreach($this->_wheres as $w) {
                $where .= $w ."=:". $w .",";
            }
            $where = rtrim($where,","); // 마지막 콤마 제거
            $where = str_replace(","," and ",$where);
            return " WHERE ".$where;
        } else {
            // where 조건이 없음.
            return "";
        }        
    }

    /**
     * 제한설정
     */
    private $_limitNum;
    private $_limitStart;
    function limit($num, $start=null)
    {
        $this->_limitNum = $num;
        $this->_limitStart = $start;
        return $this;
    }

    public function queryLimit()
    {
        $query = ""; // 초기화
        if ($this->_limitNum) {
            $query = " LIMIT ".$this->_limitNum;
        }

        if ($this->_limitStart) {
            $query = " LIMIT ".$this->_limitStart." , ".$this->_limitNum;
        }

        return $query;
    }

    private $_fields = [];
    public function setField($field)
    {
        \array_push($this->_fields, $field);
        return $this;
    }
    public function setFields($fields)
    {
        $this->_fields = $fields;
        /*
        foreach ($fields as $f) {
            $this->setField($f);
        }
        */
        return $this;
    }
    public function clear()
    {
        $this->_fields = [];
    }

        /**
     * sql 쿼리를 빌드합니다.
     */
    public function build($fields=null)
    {
        // 내부값 + 매개변수
        if (is_array($fields)) {
            foreach($fields as $value) $this->_fields [] = $value;
        }
        
        $query = $this->queryBuild($this->_fields); // 쿼리 생성
        $query .= $this->queryWhere();
        
        $query .= $this->queryLimit();

        $this->_db->setQuery($query); // connection에 쿼리설정
        return $this;
    }

    /**
     * 설정된 쿼리를 실행합니다.
     */
    public function run($data=null)
    {
        return $this->_db->run($data);
    }

}