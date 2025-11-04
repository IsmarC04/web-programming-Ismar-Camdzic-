<?php
require_once 'baseDao.php';
class UserDao extends BaseDao{
    public function __construct(){
        parent::__construct("users");
    }

    public function getByEmail($email){
        $stm = $this -> connection -> prepare ("SELECT * FROM users WHERE email=:email");
        $stm->bindParam(':email', $email);
        $stm->execute();
        return $stm->fetch();
    }

    
}


?>