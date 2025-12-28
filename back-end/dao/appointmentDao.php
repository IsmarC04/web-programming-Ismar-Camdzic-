<?php 
require_once 'BaseDao.php';

class appointmentsDao extends BaseDao {

    public function __construct() {
        parent::__construct("appointments");
    }

    
    public function createAppointment($data) {
       return $this->insert([
        "user_id"    => $data["user_id"],
        "service_id" => $data["service_id"],
        "stylist_id" => $data["stylist_id"],
        "date" => $data["date"]
    ]);

    }

    
    public function getAllAppointmentsForAdmin() {
    $stm = $this->connection->prepare("SELECT * FROM appointments");
    $stm->execute();
    return $stm->fetchAll(PDO::FETCH_ASSOC);
}

    public function getByUser($user_id){
        $stm = $this->connection->prepare("SELECT * FROM appointments WHERE user_id = :user_id");
        $stm->bindParam(":user_id", $user_id);
        $stm->execute();
        return $stm->fetchAll();
    }

    public function getByService($service_id){
        $stm = $this->connection->prepare("SELECT * FROM appointments WHERE service_id = :service_id");
        $stm->bindParam(":service_id", $service_id);
        $stm->execute();
        return $stm->fetchAll();
    }

    public function getByStylist($stylist_id){
        $stm = $this->connection->prepare("SELECT * FROM appointments WHERE stylist_id = :stylist_id");
        $stm->bindParam(":stylist_id", $stylist_id);
        $stm->execute();
        return $stm->fetchAll();
    }


    public function getAllAppoinmentsWithDetails(){
        $stm = $this->connection->prepare("select a.id, u.first_name, u.last_name, s.name as service_name, st.name as stylist_name, a.date
            from appointments a 
            left join users u on u.id = a.user_id
            left join services s  on s.id = a.service_id
            left join  stylists st on st.id = a.stylist_id
            where a.date >= CURDATE()
            order by a.date desc");
        $stm->execute();
        return $stm->fetchAll();
    }

    

}
?>
