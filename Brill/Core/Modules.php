<?php
/* 
 * Класс от которого должны наследоваться все настройки модулей
 */

/**
 * Description of Modules
 *
 * @author almaz
 */
abstract class Modules {
    protected 
        $version = 0,
        $prefix = "",
        $name,
        $defaultAction,
        $pathModule,
        $pathModels,
        $pathActions,
        $pathViews,
        $pathDB,
        $pathLib,
        $pathDaemons,
        $requiredModules = array ();

    public static function init() {
        if (self::$instance === null) {

            var_dump(__CLASS__);
         //   self::$instance = self::;
            
        }
    }

    abstract protected function configure();
    
    abstract public static function instance();
    
    function  __construct() {
        $this->pathModule   = MODULES_PATH . $this->name . '/';
        $this->pathModels   = $this->pathModule . General::NAME_DIR_MODELS .'/';
        $this->pathActions  = $this->pathModule . General::NAME_DIR_ACTIONS . '/';
        $this->pathViews    = $this->pathModule . General::NAME_DIR_VIEWS . '/';
        $this->pathDB       = $this->pathModule . General::NAME_DIR_DB . '/';
        $this->pathLib      = $this->pathModule . General::NAME_DIR_LIB . '/';
        $this->pathDaemons  = $this->pathDaemons . General::NAME_DIR_DAEMONS . '/';
        $this->configure();
    }


    /**
     * Получение значений 
     * @param string $field
     * @return
     */
    function  __get($field) {
        return $this->$field;
    }

    /**
     * Задание значений $this->values
     * @param string $field
     */
    function  __set($field, $value) {
    //    Log::warning('Read only. Задавать значения может только сам класс '.__CLASS__);
    }
}