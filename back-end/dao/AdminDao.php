<?php 
require_once 'BaseDao.php';

class AdminDao extends BaseDao{
    public function __construct(){
        parent::__construct("admins");
    }


    public function getByAdmin($name){
        $stm = $this->connection->prepare ("SELECT * FROM admins WHERE name = :name");
        $stm->bindParam(":name", $name);
        $stm->execute();
        return $stm->fetch();
    }
}
?>