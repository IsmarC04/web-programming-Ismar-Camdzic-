<?php 
require_once __DIR__ . '/../dao/stylistDao.php';
require_once 'BaseService.php';

class stylistService extends BaseService {
    public function __construct(){
        $dao = new stylistDao();
        parent::__construct($dao);
    }


    public function validateName($name){
        if(empty($name) || strlen($name) > 100){
            return [
                'success' => false,
                'message' => 'invalid name'
            ];
        }
        return ['success' => true];
    }

    public function validateEmail($email) {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return[
                'success' => false,
                'message' => 'Invalid email format'
            ];
        }
        return ['success' => true];

        

    }

    public function validatePassword($password){
        if (strlen($password) > 8){
            return [
                'success' =>false,
                'message' => 'password is too short'
            ];
        }
        return ['success' => true];


        
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $this->update($id, $hashed);
    }

    public function createStylist($name, $email, $password){
        $check = $this -> validateName($name);
        if(!$check['success']) return $check;


        $check = $this -> validateEmail($email);
        if(!$check['success']) return $check;

        $check = $this -> validatePassword($password);
        if(!$check['success']) return $check;



        $this->create([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);

        return [
            'success' => true,
            'message' => 'New service is registered.'
        ];


        
    }

}



?>