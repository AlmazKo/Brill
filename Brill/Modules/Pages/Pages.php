<?php
/*
 * SEParsing
 */

class Pages extends Modules {
    protected static $instance = null;
    protected $version = 1;
    protected $prefix = "pg_";
    protected $name = __CLASS__;
    protected $defaultAction = 'Pages';
    protected $requiredModules = array ('Auth');

    protected function configure() {}

      public  static function instance() {
        if (self::$instance === null) {
           self::$instance = new self();
        }
        return self::$instance;
    }

    public function getSettingsAccess() {
        $rights = array();
        $rights[General::GROUP_MANAGER] = array(
            'Pages'=> array(true),

        );
        $rights[General::GROUP_USER] = array(
            'Pages'=> array(true),
            );
        return $rights;
    }
}