<?php
/*
 * Auth
 * Модуль отвечающий за работу с пользователями
 */
class Auth extends Modules {
    protected static $instance = null;
    protected $version = 1;
    protected $prefix = "au_";
    protected $name = __CLASS__;
    protected $defaultAction = 'Auth';

    protected function configure() {
        require_once $this->pathDB . 'au_Stmt.php';
        require_once $this->pathLib . 'UserLib.php';
        General::$libs['UserLib'] = new UserLib();
    }

    public  static function instance() {
        if (self::$instance === null) {
           self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * дефольные значения прав доступа на модуль
     * @return array
     */
    public function getSettingsAccess() {
        $rights = array();
        $rights[General::GROUP_MANAGER] = array(
            'Users'=> array('List'),
        );
        $rights[General::GROUP_USER] = array(
            'Users'=> array(),
            );
        return $rights;
    }
}