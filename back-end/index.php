<?php
require 'vendor/autoload.php';


$origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
header("Access-Control-Allow-Origin: $origin");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}


require_once __DIR__ . '/services/userService.php';
require_once __DIR__ . '/services/adminService.php';
require_once __DIR__ . '/services/appointmentsService.php';

require_once __DIR__ . '/services/NotificationService.php';
require_once __DIR__ . '/services/reviewsService.php';
require_once __DIR__ . '/services/servicesService.php';
require_once __DIR__ . '/services/stylistService.php';
require_once __DIR__ . '/middleware/AuthMiddleware.php';
require_once __DIR__ . '/data/roles.php';



use Firebase\JWT\JWT;
use Firebase\JWT\Key;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



Flight::register('userService', 'userService');
Flight::register('appointmentsService', 'appointmentsService');
Flight::register('stylistService', 'stylistService');
Flight::register('servicesService', 'servicesService');
Flight::register('auth_middleware', 'AuthMiddleware');





Flight::before('start', function() {
   if(
       strpos(Flight::request()->url, '/login') === 0 ||
       strpos(Flight::request()->url, '/register') === 0
   ) {
       return TRUE;
   } else {
       try {
            $header = apache_request_headers();

            if(isset($header['Authorization'])) {
                $token =  str_replace('Bearer ', '', $header['Authorization']);
            } else {
                $token = '';
            }
            
           if(Flight::auth_middleware()->verifyToken($token))
               return TRUE;
            
        } catch (\Exception $e) {
           Flight::halt(401, $e->getMessage());
       }
   }
});



require_once __DIR__ . '/routes/userRoutes.php';
require_once __DIR__ . '/routes/appointmentRoute.php';
require_once __DIR__ . '/routes/servicesRoute.php';
require_once __DIR__ . '/routes/stylistRoute.php';
require_once __DIR__ . '/routes/AdminRoutes.php';



Flight::start();
?>
