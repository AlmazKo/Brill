<?php
/*
 * Auth
 * Модуль отвечающий за работу с пользователями
 */

require_once CORE_PATH . 'Modules.php';

class Auth extends Modules {
    protected static $instance = null;
    protected $version = 1;
    protected $prefix = "au_";
    protected $name = __CLASS__;
    protected $defaultAction = 'Registration';

    protected function configure() {
        require_once $this->pathLibs . 'UserLib.php';
        General::$libs['UserLib'] = new UserLib();
    }

    public  static function instance() {
        if (self::$instance === null) {
           self::$instance = new self();
        }
        return self::$instance;
    }
}