<?php
/* 
 * SEParsing
 * модуль по парсингу поисковых систем
 * а также для работы с ними
 */

class SEParsing extends Modules {
    protected static $instance = null;
    protected $version = 1;
    protected $prefix = "sep_";
    protected $name = __CLASS__;
    protected $defaultAction = 'aKeywords';
    protected $pathModels = null;
    protected $pathActions = null;
    protected $pathViews = null;

    protected function configure() {}

    public  static function instance() {
        if (self::$instance === null) {
           self::$instance = new self();
        }
        return self::$instance;
    }

}
