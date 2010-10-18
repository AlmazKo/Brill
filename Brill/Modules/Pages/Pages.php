<?php
/*
 * SEParsing
 */

require_once CORE_PATH . 'Modules.php';
// пространство имен класса Pages

class Pages extends Modules {
    static $version = 1;
    static $prefix = "pg_";
    static $name = __CLASS__;
    static $defaultAction = 'aPages';
    static $requiredModules = array ('Auth');

    protected function configure() {
        self::$pathModels = MODULES_PATH . self::$name . '/Models/';
        self::$pathActions = MODULES_PATH . self::$name . '/Actions/';
        self::$pathViews = MODULES_PATH . self::$name . '/Views/';
        
    }
  
    
}


