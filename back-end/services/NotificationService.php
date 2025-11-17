<?php 
require_once __DIR__ . '/../dao/NotificationsDao.php';
require_once 'BaseService.php';


class NotificationService extends BaseService{
    public function __construct(){
        $dao = new NotificationsDao();
        parent::__construct($dao);
    }


    public function validationFields($appointment_id, $message){
        if(!is_numeric($appointment_id || $appointment_id <= 0)){
            return [
                'success' => false,
                'message' => 'Invalid appointment Id'
            ];
        }

        if(!empty($message) && strlen($message) > 500){
            return[
                'success' => false,
                'message' => 'Description too long'
            ];
        }


        $this->create([
            'appointment_id' => $appointment_id,
            'message' => $message
        ]);

        return[
            'success' => true,
            'message'=> 'Notifications sent successfully'

        ];
    }
}

?>