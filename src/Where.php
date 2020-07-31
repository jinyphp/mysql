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
    public function where($arr)
    {
        // echo "where 쿼리\n";
        if(\jiny\is_assoArray($arr)) {
            // echo "연상배열";
            $this->_wheres = \jiny\arr_keymerge($this->_wheres, $arr);
        } else if (\is_array($arr)) {
            // echo "일반배열";
            $this->_wheres = \jiny\arr_merge($this->_wheres, $arr);
        } else if (\is_string($arr)) {
            // 문자열 쿼리
            //echo "where 문자열";
            $this->_wheres = $arr;
        }
        //exit;
        return $this;
    }

    /**
     * where 조건 쿼리를 생성합니다.
     */
    private function queryWhere()
    {
        $where = "";
        //print_r($this->_wheres);
        //echo count($this->_wheres);
        if(\jiny\is_assoArray($this->_wheres)) {
            // 연상배열 처리
            //echo "연상배열\n";
            if(!count($this->_wheres)) return ""; // 비어있는 배열
            // print_r($this->_wheres);
            foreach ($this->_wheres as $key => $value) {
                $where .= $key ."=:". $key .",";           
            }

        } else if (\is_array($this->_wheres)) {
            // 순차배열
            if(!count($this->_wheres)) return ""; // 비어있는 배열
            foreach ($this->_wheres as $key) {
                $where .= $key ."=:". $key .",";         
            }

        } else if (\is_string($this->_wheres)) {
            // 문자열 쿼리
            //echo "문자열";
            return " WHERE ".$this->_wheres;
        } else {
            return "";
        }

        //echo "pass";

        // 쿼리 문자열 보정
        $where = rtrim($where,",");
        $where = str_replace(","," and ",$where);
        return " WHERE ".$where;     
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

    protected $_fields = [];
    public function fields()
    {
        return $this->_fields;
    }
    public function setField($field)
    {
        \array_push($this->_fields, $field);
        return $this;
    }
    public function setFields($fields)
    {
        // echo __METHOD__." 필드를 설정합니다.";
        foreach ($fields as $key => $value) {
            $this->_fields[$key] = $value;
        }
        // print_r($this->_fields);
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
        // 기본쿼리 생성
        $query = $this->queryBuild($this->_fields);
        
        //echo "wehere 쿼리 생성\n";
        $query .= $this->queryWhere();
        
        //echo "limit 쿼리 생성\n";
        $query .= $this->queryLimit();

        // 생성한 쿼리를 설정함
        $this->_db->setQuery($query);
        return $this;
    }




    

    

}