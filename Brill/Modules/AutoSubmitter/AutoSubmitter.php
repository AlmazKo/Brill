<?php
/*
 * SEParsing
 */

class AutoSubmitter extends Modules {
    protected static $instance = null;
    protected $version = 1;
    protected $prefix = "as_";
    protected $name = __CLASS__;
    protected $defaultAction = 'Subscribe';
    protected $pathModels = null;
    protected $pathActions = null;
    protected $pathViews = null;
    protected $pathLib = null;

    protected function configure() {}


    public  static function instance() {
        if (self::$instance === null) {
           self::$instance = new self();
        }
        return self::$instance;
    }
}
