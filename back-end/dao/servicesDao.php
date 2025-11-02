<?php 
require_once 'BaseDao.php';

class ServicesDao extends BaseDao{
    public function __construct(){
        parent::__construct("services");
    }

    public function getByName($name){
        $stm = $this->connection -> prepare ("SELECT * FROM services WHERE name= :name");
        $stm->bindParam(':name',$name);
        $stm->execute();
        return $stm->fetch();
    }

    public function getByAdmin($admin_id){
        $stm = $this->connection -> prepare ("SELECT * FROM services WHERE admin_id=:admin_id");
        $stm->bindParam(':admin_id', $admin_id);
        $stm->execute();
        return $stm->fetchAll();
    }
}

?>