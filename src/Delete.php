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

class Delete extends Database
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



    // 선택한 id row만 삭제합니다.
    public function id($id)
    {
        $query = "DELETE FROM `".$this->_schema."`.`".$this->_tablename."` where id=:id;";
        $stmt = $this->_db->conn()->prepare($query);

        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    /**
     * 모든 데이터를 삭제합니다.
     */
    public function all()
    {
        if(!$this->_schema) {
            echo "no schema selected";
            exit;
        }

        if(!$this->_tablename) {
            echo "no table selected";
            exit;
        }

        $query = "DELETE FROM `".$this->_schema."`.`".$this->_tablename."`";
        $stmt = $this->_db->query($query);
    }

    /**
     * 삭제
     */
    public function binds($query, $bind)
    {
        $stmt = $this->_db->conn()->prepare($query);

        // 데이터를 바인드 합니다.
        foreach ($bind as $field => &$value) {
            $stmt->bindParam(':'.$field, $value);
        }

        return $stmt;
    }
    

    /**
     * 
     */
}