<?php
/*
 * SEParsing
 */

class Pages extends Modules {
    protected static $instance = null;
    protected $version = 1;
    protected $prefix = "pg_";
    protected $name = __CLASS__;
    protected $defaultAction = 'aPages';
    protected $requiredModules = array ('Auth');

    protected function configure() {}
    
      public  static function instance() {
        if (self::$instance === null) {
           self::$instance = new self();
        }
        return self::$instance;
    }
    /**
     * Заготовка для будущих инсталяций
     */
    protected function install () {
        //        $page->name = 'Главная страница';
        //        $page->content = 'Контент для главной страницы';
        //        $page->save();
    }
    
}


