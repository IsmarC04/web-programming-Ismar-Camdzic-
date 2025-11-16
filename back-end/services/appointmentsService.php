<?php 
require_once __DIR__ . '/../dao/appointmentDao.php';
require_once 'BaseService.php';


class appointmentsService extends BaseService{
    public function __construct(){
        $dao = new appointmentDao();
        parent::__construct($dao);
        
    }


    public function ValidateFields($user_id, $service_id, $stylist_id, $admin_id){
        if(!is_numeric($user_id) || $user_id <= 0){
            return [
                'success' => false,
                'message' => 'Invalid user Id'
            ];
        }
        
        
        if(!is_numeric($service_id) || $service_id <= 0){
            return [
                'success' => false,
                'message' => 'Invalid service Id'
            ];
        }




        if(!is_numeric($stylist_id) || $stylist_id <= 0){
            return [
                'success' => false,
                'message' => 'Invalid stylist Id'
            ];
        }

        
        if(!is_numeric($admin_id) || $admin_id <= 0){
            return [
                'success' => false,
                'message' => 'Invalid admin Id'
            ];
        }

        $this->create([
            'user_id' => $user_id,
            'service_id' => $service_id,
            'stylist_id' => $stylist_id,
            'admin_id' => $admin_id
        ]);


        return[
            'success' => true,
            'message' => 'New appointmnt registered'
        ];

    }
}
?>