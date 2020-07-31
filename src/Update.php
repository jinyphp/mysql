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

class Update extends Database
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
    
    private function queryBuild($fields)
    {
        //쿼리 생성
        $query = "UPDATE ".$this->_tablename." SET ";

        // 갱신 데이터
        if(is_array($fields)) {
            foreach ($fields as $key => $value) {
                if($key == "id") continue;
                if($key == "created_at") continue;
                if($key == "updated_at") {
                    $query .= "`$key`= '".date("Y-m-d H:i:s")."',";
                    continue;
                }
                $query .= "`$key`= '".$value."',";
            }
        }

        $this->_query = rtrim($query,','); // 마지막 콤마 제거
        return $this->_query;
    }

    

    /**
     * 단일 데이터 갱신
     */
    public function find($id){ return $this->id($id); } // alias
    public function id($id)
    {
        if(\is_numeric($id)) {
            $this->where("id=:id")->build();
            // echo "<br>".$this->getQuery();
            // print_r($this->fields());
            // exit;
            $this->run(['id'=>$id]);
            return $this->updateCheck();
        } else {
            echo "id 갱신은 숫자만 입력 가능합니다.";
            exit;
        }

        /*
        if(!$this->_db->getQuery()) {
            // 설정된 쿼리가 없는 경우 생성
            $this->setWhere("id");
            $this->build();
        }        
        // echo $this->_db->getQuery();

        $this->_db->setBind("id", $id);
        $this->_db->run();
        */
    }

    /**
     * 전체 데이터를 갱신합니다.
     */
    public function all()
    {
        if(!($this->_query)) { // 쿼리 생성
            $this->queryBuild($this->_fields); 
        }
        $stmt->execute();
    }

    public function binds($query, $bind)
    {
        $stmt = $this->_db->conn()->prepare($query);

        foreach ($bind as $field => &$value) {
            $stmt->bindParam(':'.$field, $value);
        }

        $stmt->execute();
        return $this;
    }

    public function updateCheck()
    {
        // database의 state값을 읽어서 확인
        $count = $this->_db->statement()->rowCount();
        if($count > 0){
            // 갱신데이터 있음.
            return true;
        } else {
            return false;
        }
    }

    /**
     * 숫자 컬럼값을 증가합니다.
     */
    public function inc($field, $num=1)
    {
        $query = "UPDATE ".$this->_tablename." SET ";
        $query .= "`updated_at`= '".date("Y-m-d H:i:s")."', ";
        $query .= "`".$field."` = `".$field."` + $num WHERE id=:id;";

        // Connection에 쿼리설정
        $this->_db->setQuery($query);

        return $this;
    }

    /**
     * 숫자 컬럼값을 감소합니다.
     */
    public function dec($field, $num=1)
    {
        $query = "UPDATE ".$this->_tablename." SET ";
        $query .= "`updated_at`= '".date("Y-m-d H:i:s")."', ";
        $query .= "`".$field."` = `".$field."` - $num  WHERE id=:id;";

        // Connection에 쿼리설정
        $this->_db->setQuery($query);

        return $this;
    }


}