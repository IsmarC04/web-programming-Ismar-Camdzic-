<?php 
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
require_once 'BaseService.php';

// Korištenje ispravne apsolutne putanje za uključivanje UserDao.php
require_once __DIR__ . '/../dao/UserDao.php'; 

class userService extends BaseService{
    public function __construct(){
        $dao = new UserDao();
        // Pozivamo konstruktor roditeljske klase i prosljeđujemo DAO
        parent::__construct($dao);
    }

    // --- REGISTRACIJA ---
    public function register($data){
        // Provjera obaveznih polja
        if(
            empty($data['first_name']) ||
            empty($data['last_name']) ||
            empty($data['email']) ||
            empty($data['password'])
        ){
            return ['success' => false, 'message' => 'All fields are required'];
        }
        
        // Provjera formata emaila
        if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
            return ['success' => false, 'message' => 'Invalid email format'];
        }
        
        // Provjera dužine lozinke
        if(strlen($data['password']) < 8){
            return ['success' => false, 'message' => 'Password must be at least 8 characters'];
        }
        
        // Provjera da li email već postoji (koristeći metodu iz UserDao)
        $existingUser = $this->dao->getByEmail($data['email']); 
        if($existingUser){
            return ['success' => false, 'message' => 'Email already exists'];
        }
        
        // Hashiranje lozinke
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $data['role'] = 'user';
        
        // Kreiranje korisnika: Pozivamo 'insert' metodu iz BaseDao
        $success = $this->dao->insert($data); 
        
        if($success){
            // Dohvatimo korisnika da bismo dobili ID i ostale podatke
            $user = $this->dao->getByEmail($data['email']);
            
            // Ukloni password iz odgovora prije vraćanja
            unset($user['password']);
            
            return [
                'success' => true,
                'message' => 'Registration successful',
                'user' => $user
            ];
        }
        
        return ['success' => false, 'message' => 'Registration failed'];
    }

    // --- PRIJAVA (LOGIN) ---
    public function login($email, $password){
        if(empty($email) || empty($password)){
            return [
                'success' => false, 
                'message' => 'Email and password are required'
            ];
        }

        // Dohvatanje korisnika iz baze
        $user = $this->dao->getByEmail($email);

        // Provjera da li korisnik postoji
        if(!$user){
            return [
                'success' => false, 
                'message' => 'Invalid email or password'
            ];
        }

        // Provjera heširane lozinke
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
                'role' => $user['role'] // obavezno
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

    // --- Dohvati korisnika po E-mailu (Wrapper za DAO) ---
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
    
    // --- PROMJENA LOZINKE ---
    public function changePassword($user_id, $newPassword){
        if(strlen($newPassword) < 8){
            return ['success' => false, 'message' => 'Password must be at least 8 characters'];
        }

        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Provjerimo da li korisnik postoji (koristeći DAO)
        $user = $this->dao->getById($user_id); 
        if(!$user){
            return ['success' => false, 'message' => 'User not found'];
        }
        
        // Ažuriranje lozinke (koristeći DAO)
        $this->dao->update($user_id, ['password' => $hashed]);

        return ['success' => true, 'message' => 'Password updated successfully'];
    }

    // --- Dohvati korisnika po ID-u ---
    public function getUserById($id){
        // Dohvatanje korisnika (koristeći DAO)
        $user = $this->dao->getById($id);

        if(!$user){
            return ['success' => false, 'message' => 'User not found'];
        }
        
        // Ukloni password iz odgovora
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

}