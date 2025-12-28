<?php 
require_once __DIR__ . '/../dao/appointmentDao.php';
require_once 'BaseService.php';


class appointmentsService extends BaseService{
    public function __construct(){
        $dao = new appointmentsDao();
        parent::__construct($dao);
        
    }


    public function ValidateFields($user_id, $service_id, $stylist_id,$date){
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

        
        
        if(empty($date)){
            return [
                'success' => false,
                'message' => 'Date is required'
            ];
        }

    

        return ['success' => true];

    }

    public function createAppointment($data){

        $user = Flight::get("user");

        $payload = [
            "user_id" => $user->id,
            "service_id" => $data['service_id'] ?? null,
            "stylist_id"=> $data['stylist_id'] ?? null,
            
            "date" => $data['date'] ?? null
        ];
        $validation = $this->ValidateFields(
            $payload['user_id'],
            $payload['service_id'],
            $payload['stylist_id'],
            
            $payload['date']
        );

        if(!$validation['success']){
            return $validation;
        }

        return $this->dao->createAppointment($payload);
    }

    public function deleteAppointments($id){
        if(!is_numeric($id)){
            return [
                'success' => false,
                'message' => "invalid ID"
            ];
        }

        $deleted = $this->delete($id);

        if($deleted){
            return [
                'success' => true,
                'message' => "Appointment deleted successfully"
            ];
        }else {
            return[
                'success' => false,
                'message' => "Appointment not found"
            ];
        }
    }


    public function getAllAppointmentsForAdmin() {
        return $this->dao->getAllAppoinmentsWithDetails();
    }

}

?>