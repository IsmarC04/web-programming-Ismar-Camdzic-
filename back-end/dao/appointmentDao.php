<?php 
require_once 'BaseDao.php';

class AppointmentsDao extends BaseDao{
    public function __construct(){
        parent::__construct("appointments");
    }


    public function getByUser($user_id){
        $stm = $this->connection -> prepare ("SELECT * FROM appointments WHERE user_id=:user_id");
        $stm->bindParam(":user_id", $user_id);
        $stm->execute();
        return $stm->fetchAll();
    }


    public function getByService($service_id){
        $stm = $this->connection->prepare ("SELECT * FROM appointments WHERE service_id=:service_id");
        $stm->bindParam(":service_id", $service_id);
        $stm->execute();
        return $stm-> fetchAll();
    }


    public function getByStylist($stylist_id){
        $stm = $this->connection ->prepare ("SELECT * FROM appointments WHERE stylist_id = :stylist_id");
        $stm->bindParam(":stylist_id", $stylist_id);
        $stm->execute();
        return $stm->fetchAll();
    }
}
?>