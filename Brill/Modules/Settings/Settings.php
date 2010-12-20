<?php
/*
 * SEParsing
 * модуль по парсингу поисковых систем
 * а также для работы с ними
 */

class Settings extends Modules {
    protected static $instance = null;
    protected $version = 1;
    protected $prefix = "st_";
    protected $name = __CLASS__;
    protected $defaultAction = 'Interfaces';
    protected $pathModels = null;
    protected $pathActions = null;
    protected $pathViews = null;

    /**
     * дефольные значения прав доступа на модуль
     * @return array
     */
    public function getSettingsAccess() {
        $rights = array();
        return $rights;
    }
    protected function configure() {
        require_once $this->pathDB . 'st_Stmt.php';

    }

    public  static function instance() {
        if (self::$instance === null) {
           self::$instance = new self();
        }
        return self::$instance;
    }

}