<?php 
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/reviewsDao.php';

class reviewsService extends BaseService{
    public function __construct(){
        $dao = new reviewsDao();

        parent::__construct($dao);
    }


    public function reviews($user_id, $stylist_id){
        if(!is_numeric($user_id) || $user_id <= 0){
            return [
                'success' => false,
                'message' => 'Invalid user Id'
            ];
        }


        if(!is_numeric($stylist_id) || $stylist_id <= 0){
            return [
                'success' => false,
                'message' => 'Invalid stylist Id'
            ];
        }


        if($user_id === $stylist_id){
            return[
                'success' => false,
                'message' => 'You cannot review yourself'
            ];
        }

        $this->create([
            'user_id' => $user_id,
            'stylist_id' => $stylist_id,

        ]);

        return[
            'success' => true,
            'message' => 'Review submited successfully'
        ];
    }
}
?>