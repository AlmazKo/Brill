<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RegistryContext
  * данные и инструкции для вью
  * @author almaz
 */

class RegistryContext extends Registry {
    protected $tpls = array();
    protected $_topTpl;
    protected static $instance = null;

    public  static function instance() {
        if (self::$instance === null) {
           self::$instance = new self();
        }
        return self::$instance;
    }

    protected function  __construct() {

    }

    function simpleAccessToVars() {
        foreach ($this->values as $key => $value) {
            if(!is_array($value) && !is_object($value)) {
                define('_' . $key, $value);
            } else {

            }

        }
    }

    function  __get($key) {
        if (array_key_exists($key, $this->values)) {
             return $this->values[$key];
        } else {
            Log::warning('Не возможно получить свойство. / ' . get_class($this) .'->' . $key . ' - не определено');
        }

    }

    public function del($key) {
        if (self::is($key)) {
            unset($this->values[$key]);
        }
    }

   public function getTpl($key) {
        if (isset($this->tpls[$key])) {
            return $this->tpls[$key];
        } else {
            return false;
        }
    }

    /**
     * Ищет шаблон в указанном модуле, создает ему абсолютный путь и сохраняет
     * @param string $key
     * @param string $nameTpl
     * @param string $module
     */
    public function setTpl($key, $nameTpl, $module = false) {
        if ($module) {
            $pathTpl = General::$loadedModules[$module]->pathViews . $nameTpl . '.php';
        } else {
            // если модуль не указан, берем текущий
            $route = Routing::instance();
            $pathTpl = General::$loadedModules[$route->module]->pathViews . $nameTpl . '.php';
        }
        if (file_exists($pathTpl)) {
            $this->tpls[$key] = $pathTpl;
        } else {
            Log::warning("Шаблон '$nameTpl' не найден по адресу: $pathTpl");
        }
   }

   /**
    * Задаает головной щаблон
    * @param <type> $nameTpl
    * @param <type> $module
    */
   public function setTopTpl($nameTpl, $module = false) {
      
        if ($module) {
            $pathTpl = General::$loadedModules[$module]->pathViews . $nameTpl . '.php';
        } else {
            // если модуль не указан, берем текущий
            $route = Routing::instance();
            $pathTpl = General::$loadedModules[$route->module]->pathViews . $nameTpl . '.php';
        }
        // Log::dump($pathTpl);
        if (file_exists($pathTpl)) {
            $this->_topTpl = $pathTpl;
        } else {
            Log::warning("Шаблон '$nameTpl' не найден по адресу: $pathTpl");
        }
   }



   public function getTopTpl(){
       return $this->_topTpl;
   }
}