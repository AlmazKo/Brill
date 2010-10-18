<?php
/* 
 * Класс от которого должны наследоваться все модули
 */

/**
 * Description of Modules
 *
 * @author almaz
 */
abstract class Modules {
    static private $instance = null;
    static $version = 0;
    static $prefix = "";
    static $name = "Undefined";
    static $defaultAction = null;
    static $pathModels = null;
    static $pathActions = null;
    static $pathViews = null;
    static $requiredModules = array ();

    public static function init() {
        if (self::$instance === null) {

            var_dump(__CLASS__);
         //   self::$instance = self::;
            
        }
    }

    abstract protected function configure();
    
    function  __construct() {
        $this->configure();
        foreach (self::$requiredModules as $module) {
           $needs = array_diff($Modules, General::$loadedModules);
           if (!$needs) {
               Log::warning('Модулю ' . self::$name . ' требуются модули: '.
                       imlode(', ', $needs));
           }

        }
    }

    
}