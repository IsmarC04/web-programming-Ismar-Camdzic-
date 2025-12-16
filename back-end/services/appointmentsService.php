<?php 
require_once __DIR__ . '/../dao/appointmentDao.php';
require_once 'BaseService.php';


class appointmentsService extends BaseService{
    public function __construct(){
        $dao = new appointmentsDao();
        parent::__construct($dao);
        
    }


    public function ValidateFields($user_id, $service_id, $stylist_id, $admin_id, $date){
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
        if(empty($date)){
            return [
                'success' => false,
                'message' => 'Date is required'
            ];
        }

    

        return ['success' => true];

    }

    public function createAppointment($data){
        $validation = $this->ValidateFields(
            $data['user_id'] ?? null,
            $data['service_id'] ?? null,
            $data['stylist_id'] ?? null,
            $data['admin_id'] ?? null,
            $data['date']?? null
        );

        if(!$validation['success']){
            return $validation;
        }

        return $this->dao->createAppointment($data);
    }



    

    public function getAllAppointmentsForAdmin() {
        return $this->dao->getAllAppointmentsForAdmin();
    }

    
    

}

?>