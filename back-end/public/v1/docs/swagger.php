<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// POKRENI BAFEROVANJE IZLAZA
ob_start(); 

require __DIR__ . '/../../../vendor/autoload.php';

if($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1'){
    define('BASE_URL', 'http://localhost/web-programming-Ismar-Camdzic-/back-end');
} else {
    define('BASE_URL', 'https://lobster-app-czvm2.ondigitalocean.app/backend/');
}

$openapi = \OpenApi\Generator::scan([
    __DIR__ . '/doc_setup.php',
    __DIR__ . '/../../../routes',
    __DIR__ . '/../../../dao'
]);

// OČISTI SVE PRETHODNO ISPRINTANO (RJEŠAVA Parser error on line 2)
ob_end_clean();

header('Content-Type: application/json');
echo $openapi->toJson();
