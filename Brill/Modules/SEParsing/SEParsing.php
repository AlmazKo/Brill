<?php
/* 
 * SEParsing
 * модуль по парсингу поисковых систем
 * а также для работы с ними
 */

class SEParsing extends Modules {
    static $version = 1;
    static $prefix = "sep_";
    static $name = __CLASS__;
    static $defaultAction = 'aKeywords';
    static $pathModels = null;
    static $pathActions = null;
    static $pathViews = null;
    //static $requiredModules = array ('Pages');

    protected function configure() {
        self::$pathModels = MODULES_PATH . self::$name . '/Models/';
        self::$pathActions = MODULES_PATH . self::$name . '/Actions/';
        self::$pathViews = MODULES_PATH . self::$name . '/Views/';

    }
}
