<?php
/*
 * SEParsing
 */

class AutoSubmitter extends Modules {
    static $version = 1;
    static $prefix = "as_";
    static $name = __CLASS__;
    static $defaultAction = 'Subscribe';
    static $pathModels = null;
    static $pathActions = null;
    static $pathViews = null;

    protected function configure() {
        self::$pathModels = MODULES_PATH . self::$name . '/Models/';
        self::$pathActions = MODULES_PATH . self::$name . '/Actions/';
        self::$pathViews = MODULES_PATH . self::$name . '/Views/';

    }
}
