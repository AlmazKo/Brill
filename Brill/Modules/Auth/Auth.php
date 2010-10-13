<?php
/*
 * Auth
 * Модуль отвечающий за работу с пользователями
 */

require_once CORE_PATH . 'Modules.php';

class Auth extends Modules {
    static $version = 1;
    static $prefix = "au_";
    static $name = __CLASS__;
    static $defaultAction = 'Auth';
    static $pathModels = null;
    static $pathActions = null;
    static $pathViews = null;

    public static function init() {
        self::$pathModels = MODULES_PATH . self::$name . '/Models/';
        self::$pathActions = MODULES_PATH . self::$name . '/Actions/';
        self::$pathViews = MODULES_PATH . self::$name . '/Views/';
    }
}


