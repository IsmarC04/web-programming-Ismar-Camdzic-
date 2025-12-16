<?php 
require_once __DIR__ . '/../dao/AdminDao.php';
require_once 'BaseService.php';

class adminService extends BaseService{
    public function __construct(){
        $dao = new AdminDao();
        parent::__construct($dao);
    }

    public function validateName($name, $email){
        if(empty($name) || strlen($name) > 100){
            return [
                'success' => false,
                'message' => 'invalid name'
            ];
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return[
                'success' => false,
                'message' => 'Invalid email format'
            ];
        }

        $this->create([
            'name' => $name,
            'email' => $email
        ]);

         return [
            'success' => true,
            'message' => 'Admin registered successfully'
        ];
    }

    

    
}

?>