<?php 
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
require_once 'BaseService.php';


require_once __DIR__ . '/../dao/UserDao.php'; 

class userService extends BaseService{
    public function __construct(){
        $dao = new UserDao();
        parent::__construct($dao);
    }

   
    public function register($data){
        
        if(
            empty($data['first_name']) ||
            empty($data['last_name']) ||
            empty($data['email']) ||
            empty($data['password'])
        ){
            return ['success' => false, 'message' => 'All fields are required'];
        }
        
        
        if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
            return ['success' => false, 'message' => 'Invalid email format'];
        }
        
        
        if(strlen($data['password']) < 8){
            return ['success' => false, 'message' => 'Password must be at least 8 characters'];
        }
        
        
        $existingUser = $this->dao->getByEmail($data['email']); 
        if($existingUser){
            return ['success' => false, 'message' => 'Email already exists'];
        }
        
       
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $data['role'] = 'user';
        
        
        $success = $this->dao->insert($data); 
        
        if($success){
            
            $user = $this->dao->getByEmail($data['email']);
            
            
            unset($user['password']);
            
            return [
                'success' => true,
                'message' => 'Registration successful',
                'user' => $user
            ];
        }
        
        return ['success' => false, 'message' => 'Registration failed'];
    }

    
    public function login($email, $password){
        if(empty($email) || empty($password)){
            return [
                'success' => false, 
                'message' => 'Email and password are required'
            ];
        }

        
        $user = $this->dao->getByEmail($email);

        
        if(!$user){
            return [
                'success' => false, 
                'message' => 'Invalid email or password'
            ];
        }

        
        if(!password_verify($password, $user['password'])){
            return [
                'success' => false,
                'message' => 'Invalid email or password'
            ];
        }
        unset($user['password']);

        $payload = [
            'user' => [
                'id' => $user['id'],
                'email' => $user['email'],
                'role' => $user['role'] 
            ],
            'iat' => time(),
            'exp' => time() + (60*60*24)
        ];
        $token = JWT::encode($payload, Config::JWT_SECRET(), 'HS256');
        
        return [
            'success' => true,
            'message' => 'Login success',
            'user' => $user,
            'token' => $token
        ];

        
    }


    public function getByEmail($email){
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return ['success' => false, 'message' => 'Invalid email format'];
        }

        $user = $this->dao->getByEmail($email);
        
        return [
            'success'=> true,
            'data' => $user
        ];
    }
    
    
    public function changePassword($user_id, $newPassword){
        if(strlen($newPassword) < 8){
            return ['success' => false, 'message' => 'Password must be at least 8 characters'];
        }

        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        
        
        $user = $this->dao->getById($user_id); 
        if(!$user){
            return ['success' => false, 'message' => 'User not found'];
        }
        
        
        $this->dao->update($user_id, ['password' => $hashed]);

        return ['success' => true, 'message' => 'Password updated successfully'];
    }

    
    public function getUserById($id){
        $user = $this->dao->getById($id);

        if(!$user){
            return ['success' => false, 'message' => 'User not found'];
        }
        
        unset($user['password']);
        
        return[
            'success' => true,
            'data' => $user
        ];
    }

    public function get_user_by_id($id) {
        return $this->dao->getById($id);
    }

    public function delete_user_by_id($id){
        return $this->dao->delete($id);
    }


    public function getAllUsers(){
        $users = $this->dao->getAll();

        if(!$users){
            return ['success' => false, 'message' => 'User not found'];
        }
        
        return[
            'success' => true,
            'data' => $users
        ];
    }

}