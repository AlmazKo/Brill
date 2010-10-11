<?php
/*
 * SEParsing
 */

require_once CORE_PATH . 'Modules.php';

class AutoSubmitter extends Modules {
    static $version = 1;
    static $prefix = "as_";
    static $name = __CLASS__;
    static $pathModels = null;
    static $pathActions = null;
    static $pathViews = null;

    public static function init() {
        self::$pathModels = MODULES_PATH . self::$name . '/Models/';
        self::$pathActions = MODULES_PATH . self::$name . '/Actions/';
        self::$pathViews = MODULES_PATH . self::$name . '/Views/';
    }
}


