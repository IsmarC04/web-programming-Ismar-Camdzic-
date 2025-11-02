<?php 
require_once 'BaseDao.php';

class ReviewsDao extends BaseDao{
    public function __construct(){
        parent::__construct("reviews");
    }

    public function getByUser($user_id){
        $stm = $this->connection->prepare("SELECT * FROM reviews WHERE user_id = :user_id");
        $stm->bindParam(':user_id', $user_id);
        $stm->execute();
        return $stm->fetchAll();
    }

    public function getByStylist($stylist_id){
        $stm = $this->connection->prepare (" SELECT * FROM reviews WHERE stylist_id =:stylist_id");
        $stm->bindParam(":stylist_id", $stylist_id);
        $stm->execute();
        return $stm->fetchAll();
    }
}

?>