<?php

// Set the reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED));

class Config {
    public static function DB_NAME() {
        return  "frizzfactory";
    }
    public static function DB_PORT() {
        return 3306;
    }
    public static function DB_USER() {
        return 'root';
    }
    public static function DB_PASSWORD() {
        return 'Root';
    }
    public static function DB_HOST() {
        return 'localhost';
    }

    public static function JWT_SECRET(){
        return 'ksdF9#Jf2!9SdfJ3kdf9@2JsdF#09';

    }
}