<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RegistryParser
 *
 * @author asuslov
 */
require_once 'Registry.php';

class RegistrySession extends Registry{

    protected static $instance = null;
    final public  static function instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    /**
     * Разбираем входящие данные
     */
    protected function __construct(){
        session_start();
    }

    public function get($key) {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        } else {
            Log::warning("'$key' нет такого свойства");
        }
    }

    public function is($key) {
        if (isset($_SESSION[$key])) {
            return true;
        } else {
            return false;
        }
    }
   public function set($key, $value) {
        $_SESSION[$key] = $value;
   }
}
?>
