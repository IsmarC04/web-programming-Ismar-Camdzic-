<?php 
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/UserDao.php';

class userService extends BaseService{
    public function __construct(){
        $dao = new userDao();
        parent::__construct($dao);
    }

    public function getByEmail($email){
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return [
                'success' => false,
                'message' => 'Invalid email format'
            ];
        }

        return [
            'success'=> true,
            'data' => $this->dao->getByEmail($email)
        ];
    }

    public function changePassword($user_id, $newPassword){
        if(strlen($newPassword) < 8){
            return [
                'success' => false,
                'message' => 'Password must be at least 8 characters'
            ];
        }

        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);

        
        $this->update($user_id, ['password' => $hashed]);

        return [
            'success' => true,
            'message' => 'Password updated successfully'
        ];
    }
}
?>
