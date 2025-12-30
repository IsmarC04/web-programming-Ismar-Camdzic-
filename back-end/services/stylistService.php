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

    public function createStylist($name, $bio){
        $check = $this -> validateName($name);
        if(!$check['success']) return $check;

        $check = $this -> validateName($bio);
        if(!$check['success']) return $bio;


        $this->create([
            'name' => $name,
            'bio' => $bio,
        ]);

        return [
            'success' => true,
            'message' => 'Stylist created successfully'
        ];


        
    }

    public function deleteStylist($id){
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
                'message' => "Stylist deleted successfully"
            ];
        }else {
            return[
                'success' => false,
                'message' => "Stylist not found"
            ];
        }
    }


    public function getStylistById($id){
        $stylist = $this->dao->getById($id);

        

        if(!$stylist){
            return ['success' => false, 'message' => 'Stylist not found'];
        }
        
        
        return[
            'success' => true,
            'data' => $stylist
        ];
    }

    public function getAllStylists(){
        $stylist = $this->dao->getAll();

        

        if(!$stylist){
            return ['success' => false, 'message' => 'Stylist not found'];
        }
        
        
        return[
            'success' => true,
            'data' => $stylist
        ];
    }

}



?>