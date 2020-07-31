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
    use Where, Limit; //trait 연결

    public function __construct($tablename, $db)
    {
        $this->_tablename = $tablename;
        $this->_db = $db;
        $this->_schema = $db->getSchema();

        // db접속 상태를 확인
        if (!$this->_db->conn()) $this->_db->connect(); 
    }

    /**
     * 쿼리문을 빌드합니다.
     */
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
        $this->_db->setQuery($query)->setBinds($data); // 쿼리 설정 및 바인드
        //print_r($data);
        $this->run($data);

        return $this->last();
    }


}