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

class Insert extends Database
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

    private function queryBuild($fields)
    {
        // 쿼리작성(bind)
        $query = "INSERT `".$this->_schema."`.`".$this->_tablename."` SET ";
        foreach($fields as $key => $value) {
            $query .= $key." = :".$key.",";
        }
        $query = rtrim($query,','); // 마지막 콤마 제거
        $query .= ";";
        return $query;
    }


    // 마지막 입력한 데이터의 id값 반환
    public function lastid() { return $this->last(); }
    public function last(){
        // 상위 DB객체의 메소드 참조
        return $this->_db->conn()->lastInsertId();
    }

    public function save($data=null)
    {
        if(!$data) $data = $this->_fields;
       
        if($data){
            $data['created_at'] = date("Y-m-d H:i:s");
            $data['updated_at'] = date("Y-m-d H:i:s");
        }        

        $query = $this->queryBuild($data); // 쿼리 생성
        $this->_db->setQuery($query)->setBinds($data);
        $this->_db->run();

        return $this->last();
    }




    /**
     * 컬럼 자동매칭
     */
    protected $_auto = false;
    public function auto($flag=true)
    {
        $this->_auto = $flag;
        return $this;
    }

    private function error($e)
    {
        if ($this->_auto) {
            $method = "_".$e->getCode();
            if(\method_exists($this,$method)) {
                // 메소드 호출
                $this->$method();
            }
        } else {
            echo $e->getCode()."\n";
            echo $e->getMessage();
        }
    }

    // 컬럼정보 불일치
    //  1054 Unknown column
    public function _42S22()
    {
        echo "컬럼 매칭 처리...";
        print_r($this->_fields);
        $this->autoField($this->_fields);


    }

    /**
     * 입력 데이터 기준, 자동 필드추가
     */
    private function autoField($data)
    {
        // 컬럼 필드 정보를 읽어 옵니다.
        $desc = $this->_db->table($this->_tablename)->desc();
        print_r($desc);

        // 배열비교 m x m
        // 연상배열
        foreach(array_keys($data) as $key) {
            for($i=0;$i<count($desc);$i++)
            if(!array_key_exists($key, $desc[$i])) {
                // $this->addField($key);
                echo "데이터 삽입 = $key \n";
            }
        }
        /*
        if(isAssoArray($data)) {
            
        } else {
            foreach($data as $key) {
                if(!array_key_exists($key, $desc)) {
                    // $this->addField($key);
                    echo "데이터 삽입 = $key \n";
                }
            }
        }
        */       
    }

    // 테이블 없음.
    public function _42S02()
    {

    }

}