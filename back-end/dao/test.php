<?php 
require_once 'UserDao.php';
require_once 'BaseDao.php';
require_once 'AdminDao.php';
require_once 'reviewsDao.php';
require_once 'stylistDao.php';
require_once 'servicesDao.php';


$reviewsDao = new reviewsDao();
$stylistDao = new stylistDao();
$servicesDao = new servicesDao();



$servicesDao->insert([
   'name' => 'kratka',
   'description' => 'kratko sa svih strana',
   'price' => 25,
   'duration'=> 15,
   'admin_id'=>1,
 
]);



$services = $servicesDao -> getAll();
print_r($services)

?>