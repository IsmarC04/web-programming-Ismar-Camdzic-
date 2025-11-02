<?php 
require_once 'BaseDao.php';

class NotificationsDao extends BaseDao {
    public function __construct(){
        parent::__construct("notifications");
    }

    public function getByAppointment($appointment_id){
        $stm = $this->connection->prepare("SELECT * FROM notifications WHERE appointment_id=:appointment_id");
        $stm->bindParam (":appointment_id", $appointment_id);
        $stm->execute();
        return $stm->fetchAll();
    }
}
?>